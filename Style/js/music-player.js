// 音乐播放器控制
class MusicPlayer {
    constructor() {
        this.player = document.getElementById('music-player');
        this.audio = document.getElementById('audio-player');
        this.playBtn = document.getElementById('play-btn');
        this.prevBtn = document.getElementById('prev-btn');
        this.nextBtn = document.getElementById('next-btn');
        this.musicName = document.getElementById('music-name');
        this.toggle = document.querySelector('.player-toggle');

        // 音乐列表
        this.playlist = [
            {
                name: '告白气球',
                url: '/admin/static/bgm/告白气球.mp3'
            },
            {
                name: '小幸运',
                url: 'music/song2.mp3'
            },
            {
                name: '遇见',
                url: 'music/song3.mp3'
            }
        ];

        this.currentIndex = 0;
        this.isPlaying = false;
        this.autoCloseTimer = null;
        this.hasInteracted = false;

        this.init();
    }

    init() {
        // 从 localStorage 恢复播放状态
        this.loadState();

        // 绑定事件
        this.toggle.addEventListener('click', () => this.togglePlayer());
        this.playBtn.addEventListener('click', () => this.handlePlayClick());
        this.prevBtn.addEventListener('click', () => this.handleControlClick(() => this.playPrev()));
        this.nextBtn.addEventListener('click', () => this.handleControlClick(() => this.playNext()));

        // 音频事件
        this.audio.addEventListener('ended', () => this.playNext());

        // 鼠标悬停时取消自动关闭
        this.player.addEventListener('mouseenter', () => this.cancelAutoClose());
        this.player.addEventListener('mouseleave', () => this.startAutoClose());

        // 加载当前歌曲
        this.loadSong(this.currentIndex);

        // 设置音频为静音并尝试播放
        this.setupAutoPlay();

        // 监听用户交互
        this.setupInteractionListeners();
    }

    setupAutoPlay() {
        // 先设置音量为0（静音）
        this.audio.volume = 0;

        // 检查之前的播放状态
        const wasPlaying = localStorage.getItem('musicWasPlaying') === 'true';

        if (wasPlaying) {
            // 尝试静音播放
            this.audio.play().then(() => {
                console.log('静音自动播放成功');
                this.isPlaying = true;
                this.playBtn.innerHTML = '<i class="fas fa-pause"></i>';
                this.playBtn.classList.add('playing');
                this.player.classList.add('playing');

                // 显示提示
                this.showAutoPlayHint();
            }).catch(error => {
                console.log('自动播放失败:', error);
                // 展开播放器提示用户
                this.player.classList.add('expanded');
                this.showPlayHint();
            });
        } else {
            // 首次访问，展开播放器
            this.player.classList.add('expanded');
            this.showPlayHint();
        }
    }

    setupInteractionListeners() {
        // 监听各种用户交互事件
        const events = ['mousedown', 'touchstart', 'keydown', 'scroll'];

        const handleInteraction = () => {
            if (!this.hasInteracted) {
                this.hasInteracted = true;

                // 如果音乐正在静音播放，恢复音量
                if (this.isPlaying && this.audio.volume === 0) {
                    this.fadeInVolume();
                }

                // 如果音乐没有播放，尝试播放
                if (!this.isPlaying && localStorage.getItem('musicWasPlaying') === 'true') {
                    this.play();
                }

                // 移除所有监听器
                events.forEach(event => {
                    document.removeEventListener(event, handleInteraction);
                });
            }
        };

        // 添加监听器
        events.forEach(event => {
            document.addEventListener(event, handleInteraction, { once: true });
        });
    }

    fadeInVolume() {
        let volume = 0;
        const fadeInterval = setInterval(() => {
            if (volume < 0.7) {
                volume += 0.1;
                this.audio.volume = Math.min(volume, 0.7);
            } else {
                clearInterval(fadeInterval);
                this.hideAutoPlayHint();
            }
        }, 100);
    }

    showAutoPlayHint() {
        const hint = document.createElement('div');
        hint.className = 'autoplay-hint';
        hint.innerHTML = '🎵 音乐已自动播放（点击页面任意位置开启声音）';
        hint.id = 'autoplay-hint';
        document.body.appendChild(hint);
    }

    hideAutoPlayHint() {
        const hint = document.getElementById('autoplay-hint');
        if (hint) {
            hint.classList.add('fade-out');
            setTimeout(() => hint.remove(), 300);
        }
    }

    showPlayHint() {
        this.musicName.textContent = '点击播放按钮开始';
        // 添加脉冲动画提示
        this.playBtn.classList.add('pulse');
    }

    togglePlayer() {
        this.player.classList.toggle('expanded');
        if (this.player.classList.contains('expanded')) {
            this.startAutoClose();
        } else {
            this.cancelAutoClose();
        }
    }

    handlePlayClick() {
        this.togglePlay();
        this.resetAutoClose();
        // 移除脉冲动画
        this.playBtn.classList.remove('pulse');
    }

    handleControlClick(action) {
        action();
        this.resetAutoClose();
    }

    startAutoClose() {
        this.cancelAutoClose();
        this.autoCloseTimer = setTimeout(() => {
            if (this.player.classList.contains('expanded')) {
                this.player.classList.remove('expanded');
            }
        }, 2000);
    }

    cancelAutoClose() {
        if (this.autoCloseTimer) {
            clearTimeout(this.autoCloseTimer);
            this.autoCloseTimer = null;
        }
    }

    resetAutoClose() {
        this.cancelAutoClose();
        this.startAutoClose();
    }

    loadSong(index) {
        const song = this.playlist[index];
        this.audio.src = song.url;
        this.musicName.textContent = song.name;
        this.currentIndex = index;
    }

    play() {
        // 确保音量正常
        if (this.hasInteracted && this.audio.volume === 0) {
            this.audio.volume = 0.7;
        }

        return this.audio.play().then(() => {
            this.isPlaying = true;
            this.playBtn.innerHTML = '<i class="fas fa-pause"></i>';
            this.playBtn.classList.add('playing');
            this.player.classList.add('playing');
            this.saveState();

            // 保存播放状态
            localStorage.setItem('musicWasPlaying', 'true');
        }).catch(error => {
            console.log('播放失败:', error);
            throw error;
        });
    }

    pause() {
        this.audio.pause();
        this.isPlaying = false;
        this.playBtn.innerHTML = '<i class="fas fa-play"></i>';
        this.playBtn.classList.remove('playing');
        this.player.classList.remove('playing');
        this.saveState();

        // 保存播放状态
        localStorage.setItem('musicWasPlaying', 'false');
    }

    togglePlay() {
        if (this.isPlaying) {
            this.pause();
        } else {
            this.play().catch(() => {
                this.musicName.textContent = '请点击播放';
            });
        }
    }

    playPrev() {
        this.currentIndex = (this.currentIndex - 1 + this.playlist.length) % this.playlist.length;
        this.loadSong(this.currentIndex);
        if (this.isPlaying) {
            this.play();
        }
    }

    playNext() {
        this.currentIndex = (this.currentIndex + 1) % this.playlist.length;
        this.loadSong(this.currentIndex);
        if (this.isPlaying) {
            this.play();
        }
    }

    saveState() {
        const state = {
            currentIndex: this.currentIndex,
            currentTime: this.audio.currentTime,
            isPlaying: this.isPlaying
        };
        localStorage.setItem('musicPlayerState', JSON.stringify(state));
    }

    loadState() {
        const state = localStorage.getItem('musicPlayerState');
        if (state) {
            const { currentIndex, currentTime, isPlaying } = JSON.parse(state);
            this.currentIndex = currentIndex || 0;

            // 恢复播放时间
            this.audio.addEventListener('loadedmetadata', () => {
                this.audio.currentTime = currentTime || 0;
            }, { once: true });
        }
    }
}

// 页面加载完成后初始化播放器
document.addEventListener('DOMContentLoaded', () => {
    window.musicPlayer = new MusicPlayer();
});

// 页面切换时保存状态
window.addEventListener('beforeunload', () => {
    if (window.musicPlayer && window.musicPlayer.audio) {
        window.musicPlayer.saveState();
    }
});