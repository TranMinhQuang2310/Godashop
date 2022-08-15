<?php
class HomeController
{
    function index()
    {
        $productRepository = new ProductRepository();
        $conds = [];
        $sorts = [
            'featured' => 'DESC'
        ];
        $page = 1;
        $item_per_page = 4;
        // Lấy 4 sản phẩm nổi bật sắp xếp theo thứ tự giảm dần cột featured
        $featuredProducts = $productRepository->getBy($conds, $sorts, $page, $item_per_page);
        //SELECT * FROM view_product ORDER BY featured DESC LIMIT 0, 4;

        // Lấy 4 sản phẩm mới nhất sắp xếp theo thứ tự giảm dần cột created_date
        $sorts = [
            'created_date' => 'DESC'
        ];
        $latestProducts = $productRepository->getBy($conds, $sorts, $page, $item_per_page);
        //SELECT * FROM view_product ORDER BY created_date DESC LIMIT 0, 4;

        //Lấy tất cả danh mục
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->getAll();
        //Lấy tất 4 sản phẩm theo từng danh mục
        foreach ($categories as $category) {
            $category_id = $category->getId();
            $conds = [
                'category_id' => [
                    'type' => '=',
                    'val' => $category_id
                ]
            ];
            //SELECT * FROM view_product WHERE category_id = 5;
            $products = $productRepository->getBy($conds, $sorts, 1, 4);
            //Lưu trữ danh sách category và product tương ứng
            $categoryProducts[] = [
                'category' => $category,
                'products' => $products
            ];
        }
        require 'view/home/index.php';
    }
}