$(document).ready(function() {
    $(document).on('click', '.delete-button', function(e) {
        e.preventDefault();

        const form = $(this).closest('.delete-form');
        const productId = form.data('product-id');
        const productName = form.data('product-name');
        const actionUrl = form.attr('action');

        if (confirm(`本当に ${productName} を削除しますか？`)) {
            $.ajax({
                url: actionUrl,
                type: 'DELETE',
                data: form.serialize(), 
                dataType: 'json'
            })
                .done(function(response) {
                    if (response.success) {
                        $(`#product-${productId}`).remove();
                        alert('商品が正常に削除されました。');
                    } else {
                        alert('削除に失敗しました: ' + response.message);
                    }
                })
                .fail(function(xhr, textStatus, errorThrown) {

                    console.error("エラー情報:", xhr);
                    console.error("ステータス:", textStatus);
                    console.error("エラーメッセージ:", errorThrown);
                    let msg = "不明なエラーが発生しました。";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        msg = xhr.responseText;
                    }
                    alert(`削除中にエラーが発生しました。\n${msg}`);
                });
        }
    });
});