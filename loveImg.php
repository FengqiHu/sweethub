<?php
include_once 'head.php';

// 分页设置
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 6; // 每页显示6个
$offset = ($page - 1) * $perPage;

// 获取总数
$countQuery = "SELECT COUNT(*) as total FROM loveImg";
$countResult = mysqli_query($connect, $countQuery);
$totalRows = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalRows / $perPage);

// 获取当前页数据
$loveImg = "SELECT * FROM loveImg ORDER BY imgDatd DESC LIMIT $offset, $perPage";
$resImg = mysqli_query($connect, $loveImg);
?>

<head>
    <link rel="stylesheet" href="Style/css/loveImg.css?LikeGirl=<?php echo $version ?>">
    <meta charset="utf-8" />
    <title><?php echo $text['title'] ?> — 恋爱相册</title>

    <style>
        /* 分页样式 */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 40px 0;
            gap: 10px;
        }

        .pagination a, .pagination span {
            display: inline-block;
            padding: 8px 15px;
            text-decoration: none;
            color: #666;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .pagination a:hover {
            background: #f0f0f0;
            color: #333;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .pagination .current {
            background: #666;
            color: white;
            border-color: #666;
        }

        .pagination .disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .page-info {
            color: #666;
            font-size: 14px;
            margin: 0 10px;
        }
    </style>
</head>

<body>
<div id="pjax-container">
    <h4 class="text-ce central">我们的回忆</h4>
    <div class="row central">
        <?php
        while ($list = mysqli_fetch_array($resImg)) {
            ?>
            <div class="img_card col-lg-4 col-md-6 col-sm-12 col-sm-x-12 <?php if ($text['Animation'] == "1") { ?>animated zoomIn delay-03s<?php } ?>">
                <div class="love_img">
                    <img data-funlazy="/admin/static/albumImg/<?php echo $list['imgUrl'] ?>" alt="<?php echo $list['imgText'] ?>"
                         data-description="<?php echo $list['imgDatd'] ?>">

                    <div class="words">
                        <i>Date：<?php echo $list['imgDatd'] ?></i>
                        <span><?php echo $list['imgText'] ?></span>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>

    <!-- 分页组件 -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <!-- 上一页 -->
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">上一页</a>
            <?php else: ?>
                <span class="disabled">上一页</span>
            <?php endif; ?>

            <!-- 页码 -->
            <?php
            $start = max(1, $page - 2);
            $end = min($totalPages, $page + 2);

            if ($start > 1) {
                echo '<a href="?page=1">1</a>';
                if ($start > 2) echo '<span>...</span>';
            }

            for ($i = $start; $i <= $end; $i++) {
                if ($i == $page) {
                    echo '<span class="current">' . $i . '</span>';
                } else {
                    echo '<a href="?page=' . $i . '">' . $i . '</a>';
                }
            }

            if ($end < $totalPages) {
                if ($end < $totalPages - 1) echo '<span>...</span>';
                echo '<a href="?page=' . $totalPages . '">' . $totalPages . '</a>';
            }
            ?>

            <!-- 下一页 -->
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>">下一页</a>
            <?php else: ?>
                <span class="disabled">下一页</span>
            <?php endif; ?>

            <!-- 页面信息 -->
            <span class="page-info">第 <?php echo $page; ?> 页，共 <?php echo $totalPages; ?> 页</span>
        </div>
    <?php endif; ?>
</div>

<?php include_once 'footer.php'; ?>
</body>
</html>