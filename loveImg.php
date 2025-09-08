<?php
include_once 'head.php';

// 分页设置
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 6; // 每页显示9个
$offset = ($page - 1) * $perPage;

// 排序设置
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'asc'; // 默认降序（最新的在前）
$sortOrder = ($sort === 'asc') ? 'ASC' : 'DESC';

// 获取总数
$countQuery = "SELECT COUNT(*) as total FROM loveImg";
$countResult = mysqli_query($connect, $countQuery);
$totalRows = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalRows / $perPage);

// 获取当前页数据
$loveImg = "SELECT * FROM loveImg ORDER BY imgDatd $sortOrder LIMIT $offset, $perPage";
$resImg = mysqli_query($connect, $loveImg);
?>

<head>
    <link rel="stylesheet" href="Style/css/loveImg.css?LikeGirl=<?php echo $version ?>">
    <meta charset="utf-8" />
    <title><?php echo $text['title'] ?> — 恋爱相册</title>

    <style>
        /* 工具栏样式 */
        .toolbar {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
            gap: 15px;
        }

        .sort-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #ddd;
            border-radius: 25px;
            color: #666;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .sort-btn:hover {
            background: #f5f5f5;
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .sort-btn.active {
            background: #666;
            color: white;
            border-color: #666;
        }

        .sort-btn i {
            font-size: 16px;
        }

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

        /* 响应式设计 */
        @media (max-width: 768px) {
            .toolbar {
                flex-direction: column;
                gap: 10px;
            }

            .sort-btn {
                width: 200px;
                justify-content: center;
            }
        }
    </style>

    <!-- 添加Font Awesome图标库 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
<div id="pjax-container">
    <h4 class="text-ce central">我们的回忆</h4>

    <!-- 排序工具栏 -->
    <div class="toolbar">
        <a href="?sort=desc&page=1" class="sort-btn <?php echo $sort === 'desc' ? 'active' : ''; ?>">
            <i class="fas fa-sort-amount-down"></i>
            <span>时间降序（最新）</span>
        </a>
        <a href="?sort=asc&page=1" class="sort-btn <?php echo $sort === 'asc' ? 'active' : ''; ?>">
            <i class="fas fa-sort-amount-up"></i>
            <span>时间升序（最早）</span>
        </a>
    </div>

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
                <a href="?page=<?php echo $page - 1; ?>&sort=<?php echo $sort; ?>">上一页</a>
            <?php else: ?>
                <span class="disabled">上一页</span>
            <?php endif; ?>

            <!-- 页码 -->
            <?php
            $start = max(1, $page - 2);
            $end = min($totalPages, $page + 2);

            if ($start > 1) {
                echo '<a href="?page=1&sort=' . $sort . '">1</a>';
                if ($start > 2) echo '<span>...</span>';
            }

            for ($i = $start; $i <= $end; $i++) {
                if ($i == $page) {
                    echo '<span class="current">' . $i . '</span>';
                } else {
                    echo '<a href="?page=' . $i . '&sort=' . $sort . '">' . $i . '</a>';
                }
            }

            if ($end < $totalPages) {
                if ($end < $totalPages - 1) echo '<span>...</span>';
                echo '<a href="?page=' . $totalPages . '&sort=' . $sort . '">' . $totalPages . '</a>';
            }
            ?>

            <!-- 下一页 -->
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>&sort=<?php echo $sort; ?>">下一页</a>
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