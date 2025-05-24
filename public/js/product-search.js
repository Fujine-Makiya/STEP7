$(document).ready(function() {
    $(document).on('click', '.delete-button', function(e) {
        e.preventDefault();

        const form = $(this).closest('.delete-form');
        const productId = form.data('product-id');
        const productName = form.data('product-name');
        const actionUrl = form.attr('action');
        console.log("AJAX リクエスト開始");0
        if (confirm(`本当に ${productName} を削除しますか？`)) {
            $.ajax({
                url: actionUrl,
                type: 'POST',
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
                    // xhrオブジェクトの詳細を確認
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
                // success: function(response) {
                //     if (response.success) {
                //         $(`#product-${productId}`).remove();
                //         alert('商品が正常に削除されました。');
                //     } else {
                //         alert('削除に失敗しました: ' + response.message);
                //     }
                // },
                // error: function(xhr) {
                //     let msg = "不明なエラーが発生しました。";
                //     if (xhr.responseJSON && xhr.responseJSON.message) {
                //         msg = xhr.responseJSON.message;
                //     }
                //     alert(`削除中にエラーが発生しました。\n${msg}`);
                // }
            // });
        }
    });

//     function retryDeleteOperation(form, productId, productName) {
//         return new Promise((resolve, reject) => {
//             setTimeout(() => {
//                 performDeleteOperation(form, productId, productName)
//                     .then(resolve)
//                     .catch(error => {
//                         currentRetry++;
//                         if (currentRetry <= maxRetries) {
//                             retryDeleteOperation(form, productId, productName)
//                                 .then(resolve)
//                                 .catch(reject);
//                         } else {
//                             reject(error);
//                         }
//                     });
//             }, 1000 * currentRetry);
//         });
//     }

//     $(document).on('click', '.delete-button', function(e) {
//         e.preventDefault();

//         var form = $(this).closest('.delete-form');
//         var productId = form.data('product-id');
//         var productName = form.data('product-name');

//         if (confirm(`本当に ${productName} を削除しますか?`)) {
//             retryDeleteOperation(form, productId, productName)
//                 .then(result => {
//                     if (result.success) {
//                         $(`#product-${result.productId}`).remove();
//                         alert('商品が正常に削除されました。');
//                     } else {
//                         alert('削除に失敗しました。システム管理者に問い合わせてください。');
//                     }
//                 })
//                 .catch(error => {
//                     console.error("Error:", error);
//                     alert("削除中にエラーが発生しました。\nエラーメッセージ: " + error.message);
//                 });
//         }
//     });
});