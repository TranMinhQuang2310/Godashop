<?php 
class ProductController {
    //trang danh sách sản phẩm
    function index() {
        //Nếu có page thì trả về page,không có page trả về 1
        $page = $_GET['page'] ?? 1;
        $item_per_page = 10;
        $conds = [];
        $sorts = [];
        //Lấy tất cả danh mục
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->getAll();

        // Khi nhấn vô các danh mục sẽ lọc ra từng sản phẩm
        $category_id = $_GET['category_id'] ?? null;
        if($category_id) {
            $conds = [
                'category_id' => [
                    'type' => '=',
                    'val' => $category_id
                ]
            ];
        }

        $priceRange = $_GET['price-range'] ?? null;
        if($priceRange) {
            $temp = explode('-' ,$priceRange);
            $startPrice = $temp[0];
            $endPrice = $temp[1];
            $conds = [
                'sale_price' => [
                    'type' => 'BETWEEN',
                    'val' => "$startPrice AND $endPrice"
                ]
            ];
            //SELECT * FROM view_product WHERE salePrice BETWEEN 10000 AND 20000

            //Dành cho khi check lớn hơn 1tr
            if($endPrice == 'greater') {
                $conds = [
                    'sale_price' => [
                        'type' => '>=',
                        'val' => $startPrice
                    ]
                ];
                //SELECT * FROM view_product WHERE salePrice >= 1tr
            }
        }

        $sort = $_GET['sort'] ?? null;
        if($sort) {
            $tmp = explode('-' , $sort);
            $colMap = ['alpha' => 'name' , 'price' => 'sale_price' , 'created' => 'created_date'];
            $colName = $colMap[$tmp[0]]; //remove later;
            $orderName = $tmp[1]; //remove later;
            $sorts = [
                $colName => $orderName
            ];
            // SELECT * FROM view_product ORDER BY sale_price DESC;
        }

        //đổ dữ liệu từ database lên
        $productRepository = new ProductRepository();
        $products = $productRepository->getBy($conds, $sorts, $page, $item_per_page);

        $totalProducts = $productRepository->getBy($conds,$sorts);
        //ceil là làm tròn số lên
        $pageTotal = ceil(count($totalProducts) / $item_per_page);
        require 'view/product/index.php';
    }

    //trang chi tiết sản phẩm
    function detail() {
        //Lấy tất cả danh mục
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->getAll();

        $id = $_GET['id'];
        $productRepository = new ProductRepository();
        $product = $productRepository->find($id);

        $category_id = $product->getCategoryId();
        $conds = [
            'category_id' => [
                'type' => '=',
                'val' => $category_id
            ],

            'id' => [
                'type' => '!=',
                'val' => $id
            ]
        ];
        //SELECT * FROM view_product WHERE category_id = 5 AND id != 2;
        $relatedProducts = $productRepository->getBy($conds, []);
        require 'view/product/detail.php';
    }

    function storeComment() {
        $data = [];
        $data["email"] = $_POST['email'];
        $data["fullname"] = $_POST['fullname'];
        $data["star"] = $_POST['rating'];
        $data["created_date"] = date('Y-m-d h:i:s');
        $data["description"] = $_POST['description'];
        $data["product_id"] = $_POST['product_id'];

        $commentRepository = new CommentRepository();
        $commentRepository->save($data);

        $productRepository = new ProductRepository();
        $product = $productRepository->find($data["product_id"]);
        require 'view/product/commentList.php';
    }
}
?>