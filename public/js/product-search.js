$(document).ready(function() {
    $('#products-table').tablesorter({
        theme: 'default',
        sortList: [[0, 0]], 
        widgets: ['zebra', 'filter']
    });
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
                        $('#products-table').trigger('update');
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
$('#search-button').on('click', function(e) {
        e.preventDefault();
        performSearch();
    });
    function performSearch() {
        const formData = $('#search-form').serialize();
        const url = $('#search-form').data('search-url');
        $.ajax({
            url: url,
            type: 'GET',
            data: formData,
            dataType: 'json',
            success: function(response) {
                updateProductTable(response);
            },
            error: function(xhr, status, error) {
                console.error('Search error:', error);
                console.error('Status:', status);
                console.error('Response text:', xhr.responseText);
                console.error('Response JSON:', xhr.responseJSON);
                alert('検索に失敗しました。');
            }
        });
    }
    function updateProductTable(response) {
        const tbody = $('#products-table tbody');
        tbody.find('tr').remove();
        tbody.empty();
        if (!response.data || response.data.length === 0) {
            tbody.append('<tr><td colspan="7" class="text-center">該当する商品が見つかりません。</td></tr>');
            return;
        }
        response.data.forEach(function(product) {
            const imgPath = product.img_path ? product.img_path : '/storage/products/default.jpg';
            const row = `
                <tr id="product-${product.id}">
                    <td>${product.id}</td>
                    <td><img src="${imgPath}" alt="商品画像" width="100"></td>
                    <td>${product.product_name}</td>
                    <td data-text="${product.price}">${product.price}</td>
                    <td data-text="${product.stock}">${product.stock}</td>
                    <td>${product.company ? product.company.company_name : 'N/A'}</td>
                    <td>
                        <a href="/products/${product.id}" class="btn btn-info btn-sm mx-1">詳細</a>
                        <form method="POST"
                              action="/products/${product.id}"
                              class="delete-form"
                              data-product-id="${product.id}"
                              data-product-name="${product.product_name}">
                            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger btn-sm mx-1 delete-button">削除</button>
                        </form>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
        $('#products-table').trigger('update');
    }
});








