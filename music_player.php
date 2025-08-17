<?php
// 检查当前路径是否为 admin
$current_path = $_SERVER['REQUEST_URI'];
$is_admin = strpos($current_path, '/admin') !== false;

// 如果不是 admin 路径，则显示音乐播放器
if (!$is_admin):
    ?>
    <!-- 音乐播放器 -->
    <div id="music-player" class="music-player">
        <div class="player-toggle">
            <i class="fas fa-music"></i>
        </div>
        <div class="player-controls">
            <button class="control-btn" id="prev-btn">
                <i class="fas fa-step-backward"></i>
            </button>
            <button class="control-btn" id="play-btn">
                <i class="fas fa-play"></i>
            </button>
            <button class="control-btn" id="next-btn">
                <i class="fas fa-step-forward"></i>
            </button>
            <div class="music-info">
                <span id="music-name">Loading...</span>
            </div>
        </div>
        <audio id="audio-player" preload="auto"></audio>
    </div>

    <!-- 首次访问提示 -->
    <div id="music-overlay" style="
  position:fixed;top:0;left:0;width:100%;height:100%;
  background:rgba(0,0,0,0.5);color:#fff;display:flex;
  align-items:center;justify-content:center;z-index:2000;">
        点击开启音乐 🎵
    </div>

    <style>
        .music-player {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 15px;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .music-player:hover {
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.15);
        }

        .player-toggle {
            display: inline-block;
            cursor: pointer;
            font-size: 20px;
            color: #ff6b6b;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        .player-controls {
            display: none;
            align-items: center;
            gap: 15px;
            margin-left: 10px;
        }

        .music-player.expanded .player-toggle {
            display: none;
        }

        .music-player.expanded .player-controls {
            display: flex;
        }

        .control-btn {
            background: none;
            border: none;
            color: #333;
            font-size: 18px;
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.3s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .control-btn:hover {
            background: #f0f0f0;
            color: #ff6b6b;
        }

        .music-info {
            margin-left: 10px;
            font-size: 14px;
            color: #666;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* 音乐提示 */
        .music-toast {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 107, 107, 0.9);
            color: white;
            padding: 12px 24px;
            border-radius: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            display: none;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            z-index: 1001;
            animation: slideDown 0.5s ease;
        }

        .music-toast.show {
            display: flex;
        }

        @keyframes slideDown {
            from {
                transform: translateX(-50%) translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }
        }

        /* 响应式设计 */
        @media (max-width: 768px) {
            .music-player {
                bottom: 20px;
                right: 20px;
                padding: 12px;
            }

            .control-btn {
                font-size: 16px;
                width: 35px;
                height: 35px;
            }

            .music-info {
                max-width: 100px;
                font-size: 12px;
            }

            .music-toast {
                font-size: 12px;
                padding: 10px 20px;
            }
        }
    </style>

    <script>

        // 音乐播放器功能
        document.addEventListener('DOMContentLoaded', function () {
            // 音乐列表
            const musicList = [
                {name: '歌曲1', file: '1.mp3'},
                {name: '歌曲2', file: 'song2.mp3'},
                {name: '歌曲3', file: 'song3.mp3'},
                // 添加更多歌曲
            ];

            let currentIndex = 0;
            let isPlaying = false;
            let hasInteracted = false;
            let isInitialized = false;

            const player = document.getElementById('music-player');
            const audio = document.getElementById('audio-player');
            const playBtn = document.getElementById('play-btn');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const musicName = document.getElementById('music-name');
            const playerToggle = document.querySelector('.player-toggle');
            const musicToast = document.getElementById('music-toast');

            // 从 localStorage 获取播放状态
            function loadSavedState() {
                const savedState = localStorage.getItem('musicPlayerState');
                if (savedState) {
                    try {
                        const state = JSON.parse(savedState);
                        currentIndex = state.currentIndex || 0;
                        isPlaying = state.isPlaying !== false; // 默认为 true
                        audio.currentTime = state.currentTime || 0;
                        hasInteracted = state.hasInteracted || false;
                    } catch (e) {
                        console.error('Failed to parse saved state:', e);
                    }
                }
            }

            // 初始化播放器
            function initPlayer() {
                if (isInitialized) return;
                isInitialized = true;

                loadSavedState();
                loadMusic(currentIndex);

                // 如果之前已经在播放且用户已交互过，尝试自动播放
                if (isPlaying && hasInteracted) {
                    setTimeout(() => tryAutoPlay(), 100);
                } else if (!hasInteracted) {
                    // 首次访问，显示提示
                    showMusicToast();
                }
            }

            // 尝试自动播放
            function tryAutoPlay() {
                if (!audio.src) {
                    loadMusic(currentIndex);
                }

                const playPromise = audio.play();

                if (playPromise !== undefined) {
                    playPromise.then(() => {
                        console.log('Auto-play started successfully');
                        isPlaying = true;
                        updatePlayButton();
                        player.classList.add('expanded');
                        saveState();
                    }).catch(error => {
                        console.log('Auto-play failed:', error);
                        // 尝试静音播放
                        audio.muted = true;
                        audio.play().then(() => {
                            isPlaying = true;
                            updatePlayButton();
                            player.classList.add('expanded');
                            // 1秒后取消静音
                            setTimeout(() => {
                                audio.muted = false;
                            }, 1000);
                        }).catch(e => {
                            console.log('Muted auto-play also failed');
                            isPlaying = false;
                            updatePlayButton();
                            if (!hasInteracted) {
                                showMusicToast();
                            }
                        });
                    });
                }
            }

            // 显示音乐提示
            function showMusicToast() {
                musicToast.classList.add('show');
                setTimeout(() => {
                    musicToast.classList.remove('show');
                }, 5000);
            }

            // 加载音乐
            function loadMusic(index) {
                if (index >= 0 && index < musicList.length) {
                    const music = musicList[index];
                    audio.src = `/admin/static/bgm/${music.file}`;
                    musicName.textContent = music.name;
                    updatePlayButton();
                }
            }

            // 播放/暂停
            function togglePlay() {
                if (!audio.src) {
                    loadMusic(currentIndex);
                }

                if (isPlaying) {
                    audio.pause();
                    isPlaying = false;
                } else {
                    audio.play().then(() => {
                        isPlaying = true;
                        updatePlayButton();
                    }).catch(e => {
                        console.error('Play failed:', e);
                    });
                }
                updatePlayButton();
                saveState();
            }

            // 上一首
            function prevMusic() {
                currentIndex = (currentIndex - 1 + musicList.length) % musicList.length;
                loadMusic(currentIndex);
                if (isPlaying) {
                    audio.play().catch(e => console.error('Play failed:', e));
                }
                saveState();
            }

            // 下一首
            function nextMusic() {
                currentIndex = (currentIndex + 1) % musicList.length;
                loadMusic(currentIndex);
                if (isPlaying) {
                    audio.play().catch(e => console.error('Play failed:', e));
                }
                saveState();
            }

            // 更新播放按钮图标
            function updatePlayButton() {
                if (isPlaying) {
                    playBtn.innerHTML = '<i class="fas fa-pause"></i>';
                    playBtn.classList.add('playing');
                } else {
                    playBtn.innerHTML = '<i class="fas fa-play"></i>';
                    playBtn.classList.remove('playing');
                }
            }

            // 保存播放状态
            function saveState() {
                const state = {
                    currentIndex: currentIndex,
                    isPlaying: isPlaying,
                    currentTime: audio.currentTime,
                    hasInteracted: hasInteracted
                };
                localStorage.setItem('musicPlayerState', JSON.stringify(state));
            }

            // 首次用户交互时自动播放
            function handleFirstInteraction(e) {
                console.log('First interaction detected');

                if (!hasInteracted) {
                    hasInteracted = true;

                    // 如果还没有初始化，先初始化
                    if (!isInitialized) {
                        initPlayer();
                    }

                    // 如果音频还没有加载，先加载
                    if (!audio.src) {
                        loadMusic(currentIndex);
                    }

                    // 尝试播放
                    setTimeout(() => {
                        audio.play().then(() => {
                            console.log('Music started playing after interaction');
                            isPlaying = true;
                            updatePlayButton();
                            player.classList.add('expanded');
                            musicToast.classList.remove('show');
                            saveState();
                        }).catch(error => {
                            console.error('Failed to play after interaction:', error);
                        });
                    }, 100);
                }
            }

            // 设置事件监听器
            function setupEventListeners() {
                // 监听首次用户交互 - 使用捕获阶段以确保优先执行
                if (!hasInteracted) {
                    document.addEventListener('click', handleFirstInteraction, true);
                    document.addEventListener('touchstart', handleFirstInteraction, true);
                    document.addEventListener('keydown', handleFirstInteraction, true);
                }

                // 展开/收起播放器
                playerToggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    player.classList.add('expanded');
                    if (!hasInteracted) {
                        handleFirstInteraction(e);
                    }
                });

                // 点击播放器外部收起
                document.addEventListener('click', function (e) {
                    if (!player.contains(e.target) && player.classList.contains('expanded')) {
                        player.classList.remove('expanded');
                    }
                });

                // 控制按钮事件
                playBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    togglePlay();
                });

                prevBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    prevMusic();
                });

                nextBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    nextMusic();
                });

                // 音乐结束时自动下一首
                audio.addEventListener('ended', nextMusic);

                // 监听页面可见性变化
                document.addEventListener('visibilitychange', function () {
                    if (document.hidden) {
                        saveState();
                    } else {
                        if (isPlaying && hasInteracted) {
                            audio.play().catch(e => console.log('Resume play failed'));
                        }
                    }
                });

                // 页面卸载时保存状态
                window.addEventListener('beforeunload', saveState);
            }

            // 定期保存播放进度
            setInterval(() => {
                if (isPlaying && !audio.paused) {
                    saveState();
                }
            }, 5000);

            // 初始化
            initPlayer();
            setupEventListeners();
        });
    </script>

    <!-- 引入 Font Awesome 图标库（如果还没有引入） -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<?php endif; ?>