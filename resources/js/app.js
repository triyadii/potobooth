document.addEventListener('DOMContentLoaded', () => {
    // UI Elements
    const video = document.getElementById('webcam');
    const cameraPlaceholder = document.getElementById('camera-placeholder');
    const btnStartCamera = document.getElementById('btn-start-camera');
    const btnCapture = document.getElementById('btn-capture');
    
    const layoutCards = document.querySelectorAll('.layout-card');
    const themeCards = document.querySelectorAll('.theme-card');
    const frameTextInput = document.getElementById('frame-text');
    const charCountText = document.querySelector('.char-count');
    
    // Custom Theme Designer inputs
    const customThemePanel = document.getElementById('custom-theme-panel');
    const customBgColorInput = document.getElementById('custom-bg-color');
    const customBorderColorInput = document.getElementById('custom-border-color');
    const customTextColorInput = document.getElementById('custom-text-color');
    const customBorderWidthInput = document.getElementById('custom-border-width');
    const customBorderWidthVal = document.getElementById('custom-border-width-val');
    const customFontFamilySelect = document.getElementById('custom-font-family');
    const customOverlayTypeSelect = document.getElementById('custom-overlay-type');
    const customBgImageInput = document.getElementById('custom-bg-image');
    const customImagePreview = document.getElementById('custom-image-preview');
    const customImagePreviewWrapper = document.getElementById('custom-image-preview-wrapper');
    const btnClearBgImage = document.getElementById('btn-clear-bg-image');
    const imageTypeGroup = document.getElementById('image-type-group');
    const customImageTypeSelect = document.getElementById('custom-image-type');
    
    const countdownOverlay = document.getElementById('countdown-overlay');
    const countdownNumber = document.getElementById('countdown-number');
    const captureStatus = document.getElementById('capture-status');
    const captureStatusText = document.getElementById('capture-status-text');
    const flashOverlay = document.getElementById('flash-overlay');
    const miniProgress = document.getElementById('mini-progress');
    
    const resultPlaceholder = document.getElementById('result-placeholder');
    const stripPreviewWrapper = document.getElementById('strip-preview-wrapper');
    const finalStripImg = document.getElementById('final-strip-img');
    const filterSection = document.querySelector('.filter-section');
    const filterCards = document.querySelectorAll('.filter-card');
    
    const resultActions = document.querySelector('.result-actions');
    const btnPrint = document.getElementById('btn-print');
    const btnDownload = document.getElementById('btn-download');
    const btnRetake = document.getElementById('btn-retake');
    
    const printImage = document.getElementById('print-image');
    
    // Audio Context for synthesizing camera sounds (Self-contained, no external URLs)
    let audioCtx = null;
    
    function initAudio() {
        if (!audioCtx) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
    }
    
    function playBeep(frequency = 880, duration = 0.1) {
        initAudio();
        if (!audioCtx) return;
        
        try {
            const osc = audioCtx.createOscillator();
            const gainNode = audioCtx.createGain();
            
            osc.type = 'sine';
            osc.frequency.setValueAtTime(frequency, audioCtx.currentTime);
            
            gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + duration);
            
            osc.connect(gainNode);
            gainNode.connect(audioCtx.destination);
            
            osc.start();
            osc.stop(audioCtx.currentTime + duration);
        } catch (e) {
            console.warn('Audio synthesis failed:', e);
        }
    }
    
    function playShutterSound() {
        initAudio();
        if (!audioCtx) return;
        
        try {
            // Generate synthetic mechanical camera click
            const bufferSize = audioCtx.sampleRate * 0.15; // 0.15s noise
            const buffer = audioCtx.createBuffer(1, bufferSize, audioCtx.sampleRate);
            const data = buffer.getChannelData(0);
            
            for (let i = 0; i < bufferSize; i++) {
                data[i] = Math.random() * 2 - 1;
            }
            
            const noise = audioCtx.createBufferSource();
            noise.buffer = buffer;
            
            const filter = audioCtx.createBiquadFilter();
            filter.type = 'highpass';
            filter.frequency.setValueAtTime(1000, audioCtx.currentTime);
            filter.frequency.exponentialRampToValueAtTime(8000, audioCtx.currentTime + 0.08);
            
            const gainNode = audioCtx.createGain();
            gainNode.gain.setValueAtTime(0.4, audioCtx.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.15);
            
            noise.connect(filter);
            filter.connect(gainNode);
            gainNode.connect(audioCtx.destination);
            
            noise.start();
        } catch (e) {
            console.warn('Audio synthesis failed:', e);
        }
    }

    // App State Variables
    let stream = null;
    let cameraActive = false;
    let activeFramesCount = 4;
    let activeLayoutId = '2x2';
    let activeThemeId = 'retro';
    let activeFilter = 'normal';
    let activeOrientation = 'landscape';
    let capturedFrames = []; // Array of ImageData object or dataURLs
    let isCapturing = false;
    
    // Custom theme configuration state
    let customThemeConfig = {
        bgColor: '#ffffff',
        borderColor: '#111111',
        textColor: '#111111',
        borderWidth: 12,
        fontFamily: 'Inter',
        overlayType: 'none',
        bgImage: null,
        imageType: 'overlay'
    };
    
    // Theme Styling configurations
    const themeStyles = {
        retro: {
            bgColor: '#f4ebe1',
            textColor: '#4a3b32',
            borderColor: '#4a3b32',
            borderWidth: 8,
            font: 'italic bold 28px "Playfair Display", serif',
            drawOverlay: (ctx, width, height) => {
                // Retro Vignette & Grain
                ctx.fillStyle = 'rgba(74, 59, 50, 0.03)';
                ctx.fillRect(0, 0, width, height);
                
                // Outer ornamental frame lines
                ctx.strokeStyle = 'rgba(74, 59, 50, 0.4)';
                ctx.lineWidth = 1;
                ctx.strokeRect(15, 15, width - 30, height - 30);
            }
        },
        pastel: {
            bgColor: '#ffe5ec',
            textColor: '#6c5ce7',
            borderColor: '#ffb3c6',
            borderWidth: 10,
            font: 'bold 28px "Fredoka", sans-serif',
            drawOverlay: (ctx, width, height) => {
                // Cute Hearts/Stars drawn randomly
                ctx.fillStyle = '#ffb3c6';
                
                const drawHeart = (x, y, size) => {
                    ctx.beginPath();
                    ctx.moveTo(x, y + size/4);
                    ctx.quadraticCurveTo(x, y, x + size/2, y);
                    ctx.quadraticCurveTo(x + size, y, x + size, y + size/3);
                    ctx.quadraticCurveTo(x + size, y + size*2/3, x + size/2, y + size);
                    ctx.quadraticCurveTo(x, y + size*2/3, x - size/2, y + size);
                    ctx.quadraticCurveTo(x - size, y + size*2/3, x - size, y + size/3);
                    ctx.quadraticCurveTo(x - size, y, x - size/2, y);
                    ctx.quadraticCurveTo(x, y, x, y + size/4);
                    ctx.closePath();
                    ctx.fill();
                };
                
                // Draw decorative hearts at bottom corners
                ctx.save();
                ctx.globalAlpha = 0.55;
                drawHeart(40, height - 90, 16);
                drawHeart(width - 60, height - 90, 16);
                drawHeart(width/2 - 120, height - 42, 12);
                drawHeart(width/2 + 120, height - 42, 12);
                ctx.restore();
            }
        },
        cyberpunk: {
            bgColor: '#0d0e15',
            textColor: '#00f0ff',
            borderColor: '#ff007f',
            borderWidth: 6,
            font: '800 24px "Orbitron", sans-serif',
            drawOverlay: (ctx, width, height) => {
                // Tech grid pattern in background
                ctx.strokeStyle = 'rgba(255, 0, 127, 0.15)';
                ctx.lineWidth = 1;
                
                // Draw horizontal line grids
                for (let y = 0; y < height; y += 40) {
                    ctx.beginPath();
                    ctx.moveTo(0, y);
                    ctx.lineTo(width, y);
                    ctx.stroke();
                }
                
                // Draw neon borders around frame
                ctx.shadowColor = '#ff007f';
                ctx.shadowBlur = 15;
                ctx.strokeStyle = '#ff007f';
                ctx.lineWidth = 3;
                ctx.strokeRect(10, 10, width - 20, height - 20);
                
                // Outer cyan border
                ctx.shadowColor = '#00f0ff';
                ctx.shadowBlur = 10;
                ctx.strokeStyle = '#00f0ff';
                ctx.lineWidth = 1;
                ctx.strokeRect(5, 5, width - 10, height - 10);
                
                // Reset shadows
                ctx.shadowColor = 'transparent';
                ctx.shadowBlur = 0;
            }
        },
        classic: {
            bgColor: '#ffffff',
            textColor: '#1a1a1a',
            borderColor: '#1a1a1a',
            borderWidth: 12,
            font: 'bold 28px "Cinzel", serif',
            drawOverlay: (ctx, width, height) => {
                // Double frame styling
                ctx.strokeStyle = '#1a1a1a';
                ctx.lineWidth = 1.5;
                ctx.strokeRect(20, 20, width - 40, height - 40);
                ctx.strokeRect(24, 24, width - 48, height - 48);
            }
        },
        party: {
            bgColor: '#121212',
            textColor: '#ffd700',
            borderColor: '#ffd700',
            borderWidth: 8,
            font: '800 26px "Outfit", sans-serif',
            drawOverlay: (ctx, width, height) => {
                // Draw confetti shapes
                ctx.save();
                const colors = ['#ffd700', '#ff0055', '#00f0ff', '#ffffff'];
                for (let i = 0; i < 40; i++) {
                    const x = Math.random() * width;
                    const y = Math.random() * height;
                    
                    // Only draw confetti if it is in the frame margins (not directly covering photos)
                    const onMargin = x < 40 || x > (width - 40) || y < 40 || y > (height - 90);
                    if (onMargin) {
                        ctx.fillStyle = colors[Math.floor(Math.random() * colors.length)];
                        ctx.beginPath();
                        if (Math.random() > 0.4) {
                            ctx.arc(x, y, Math.random() * 3 + 2, 0, Math.PI * 2);
                        } else {
                            ctx.rect(x, y, Math.random() * 6 + 2, Math.random() * 6 + 2);
                        }
                        ctx.fill();
                    }
                }
                ctx.restore();
            }
        }
    };

    // Filter properties mapping to Canvas 2D ctx.filter
    const filterStyles = {
        normal: 'none',
        vintage: 'sepia(70%) contrast(0.95) brightness(1.05) saturate(1.1)',
        bw: 'grayscale(100%) contrast(1.25) brightness(0.95)',
        cyber: 'hue-rotate(180deg) saturate(1.8) contrast(1.15)',
        warm: 'sepia(20%) saturate(1.35) brightness(1.05) contrast(0.98) hue-rotate(-5deg)'
    };

    // Initialize Camera WebRTC
    async function startCamera() {
        // Check if API is blocked by browser due to Insecure HTTP Origin
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            console.error('WebRTC MediaDevices API is not available on this origin.');
            alert('Gagal Mengakses Kamera (Batasan Keamanan Browser):\n\nKamera hanya dapat diakses melalui koneksi aman (HTTPS) atau melalui alamat lokal loopback.\n\nSilakan buka aplikasi menggunakan alamat:\n👉 http://127.0.0.1:8080  atau  http://localhost:8080\n\nJika menggunakan domain kustom potobooth.local, Anda harus mengaktifkan HTTPS atau mengaktifkan flag peramban untuk mengizinkan kamera di asal tidak aman.');
            return;
        }

        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: { ideal: 1280 },
                    height: { ideal: 960 },
                    facingMode: 'user'
                },
                audio: false
            });
            
            video.srcObject = stream;
            cameraActive = true;
            
            // Adjust states UI
            cameraPlaceholder.classList.remove('active');
            btnCapture.removeAttribute('disabled');
            
        } catch (err) {
            console.error('Error accessing webcam:', err);
            alert('Gagal mengakses kamera. Pastikan Anda memberikan izin akses kamera di peramban Anda.');
        }
    }

    // Stop Camera stream
    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        cameraActive = false;
        cameraPlaceholder.classList.add('active');
        btnCapture.setAttribute('disabled', 'true');
    }

    // Event Handlers Setup
    btnStartCamera.addEventListener('click', () => {
        // iOS requires user interaction to initialize Audio Context
        initAudio();
        startCamera();
    });

    // Character Count tracker for Frame Text input
    frameTextInput.addEventListener('input', (e) => {
        const text = e.target.value;
        charCountText.textContent = `${text.length}/25`;
        // Live update the canvas if an image is already generated
        if (capturedFrames.length === activeFramesCount) {
            generateFinalStrip();
        }
    });

    // Screen Aspect Ratio Option listeners
    const btnOrientationLandscape = document.getElementById('btn-orientation-landscape');
    const btnOrientationPortrait = document.getElementById('btn-orientation-portrait');
    const cameraContainer = document.querySelector('.camera-container');

    if (btnOrientationLandscape && btnOrientationPortrait) {
        const toggleOrientation = (newOrientation) => {
            if (isCapturing) return;
            activeOrientation = newOrientation;

            if (newOrientation === 'portrait') {
                btnOrientationPortrait.classList.remove('btn-light');
                btnOrientationPortrait.classList.add('btn-primary');
                btnOrientationLandscape.classList.remove('btn-primary');
                btnOrientationLandscape.classList.add('btn-light');
                if (cameraContainer) cameraContainer.classList.add('portrait-mode');
            } else {
                btnOrientationLandscape.classList.remove('btn-light');
                btnOrientationLandscape.classList.add('btn-primary');
                btnOrientationPortrait.classList.remove('btn-primary');
                btnOrientationPortrait.classList.add('btn-light');
                if (cameraContainer) cameraContainer.classList.remove('portrait-mode');
            }

            // Re-render final strip if capture is completed
            if (capturedFrames.length === activeFramesCount) {
                generateFinalStrip();
            }
        };

        btnOrientationLandscape.addEventListener('click', () => toggleOrientation('landscape'));
        btnOrientationPortrait.addEventListener('click', () => toggleOrientation('portrait'));
    }

    // Layout configuration click selection
    layoutCards.forEach(card => {
        card.addEventListener('click', () => {
            if (isCapturing) return;
            layoutCards.forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            activeFramesCount = parseInt(card.dataset.frames);
            activeLayoutId = card.dataset.layoutId;
        });
    });

    // Theme select card click selection
    themeCards.forEach(card => {
        card.addEventListener('click', (e) => {
            // Prevent event from bubbling if delete button was clicked
            if (e.target.closest('.btn-delete-custom-theme')) {
                return;
            }
            
            themeCards.forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            
            const isCustom = card.dataset.isCustom === 'true';
            
            if (isCustom) {
                activeThemeId = 'custom';
                customThemePanel.classList.add('active');
                
                // Load custom theme properties
                customThemeConfig.bgColor = card.dataset.bgColor;
                customThemeConfig.borderColor = card.dataset.borderColor;
                customThemeConfig.textColor = card.dataset.textColor;
                customThemeConfig.borderWidth = parseInt(card.dataset.borderWidth);
                customThemeConfig.fontFamily = card.dataset.fontFamily;
                customThemeConfig.overlayType = card.dataset.overlayType;
                customThemeConfig.bgImage = card.dataset.bgImage || null;
                customThemeConfig.imageType = card.dataset.imageType || 'overlay';
                
                // Update inputs UI values
                customBgColorInput.value = customThemeConfig.bgColor;
                customBorderColorInput.value = customThemeConfig.borderColor;
                customTextColorInput.value = customThemeConfig.textColor;
                customBorderWidthInput.value = customThemeConfig.borderWidth;
                customBorderWidthVal.textContent = `${customThemeConfig.borderWidth}px`;
                customFontFamilySelect.value = customThemeConfig.fontFamily;
                customOverlayTypeSelect.value = customThemeConfig.overlayType;
                customImageTypeSelect.value = customThemeConfig.imageType;

                // Handle custom image template pre-population in UI
                if (customThemeConfig.bgImage) {
                    customImagePreview.src = customThemeConfig.bgImage;
                    customImagePreviewWrapper.style.display = 'block';
                    imageTypeGroup.style.display = 'block';
                } else {
                    customBgImageInput.value = '';
                    customImagePreview.src = '';
                    customImagePreviewWrapper.style.display = 'none';
                    imageTypeGroup.style.display = 'none';
                }
            } else {
                activeThemeId = card.dataset.theme;
                // Show custom designer panel only if "custom" (new layout designer) card is clicked
                if (activeThemeId === 'custom') {
                    customThemePanel.classList.add('active');
                } else {
                    customThemePanel.classList.remove('active');
                }
            }
            
            // Re-render final strip if capture is completed
            if (capturedFrames.length === activeFramesCount) {
                generateFinalStrip();
            }
        });
    });

    // Filter selectors selection
    filterCards.forEach(card => {
        card.addEventListener('click', () => {
            filterCards.forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            activeFilter = card.dataset.filter;
            
            // Re-render final strip if capture is completed
            if (capturedFrames.length === activeFramesCount) {
                generateFinalStrip();
            }
        });
    });

    // Capture Trigger Flow
    btnCapture.addEventListener('click', async () => {
        if (isCapturing || !cameraActive) return;
        
        isCapturing = true;
        capturedFrames = [];
        miniProgress.innerHTML = '';
        btnCapture.classList.add('capturing');
        
        // Hide result display
        resultPlaceholder.classList.add('active');
        stripPreviewWrapper.classList.remove('active');
        filterSection.style.display = 'none';
        resultActions.style.display = 'none';
        
        // Disable settings panel during active shoot
        disableSettingsPanel(true);
        
        // Execute capture loop sequentially
        for (let frameIndex = 0; frameIndex < activeFramesCount; frameIndex++) {
            await captureSingleFrame(frameIndex);
            // Small breathing delay between photos (except the last one)
            if (frameIndex < activeFramesCount - 1) {
                await delay(2000);
            }
        }
        
        // Finished capturing sequence
        isCapturing = false;
        btnCapture.classList.remove('capturing');
        disableSettingsPanel(false);
        
        // Generate and preview final photobooth canvas strip
        generateFinalStrip();
        
        // Show result display
        resultPlaceholder.classList.remove('active');
        stripPreviewWrapper.classList.add('active');
        filterSection.style.display = 'block';
        resultActions.style.display = 'flex';
    });

    // Helper functions
    function disableSettingsPanel(disable) {
        layoutCards.forEach(card => {
            if (disable) card.style.pointerEvents = 'none';
            else card.style.pointerEvents = 'auto';
        });
        frameTextInput.disabled = disable;
    }

    function delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    // Capture individual frame with countdown and shutter synthesis
    function captureSingleFrame(index) {
        return new Promise((resolve) => {
            // Show status
            captureStatusText.textContent = `FOTO ${index + 1}/${activeFramesCount}`;
            captureStatus.classList.add('active');
            
            let count = 3;
            countdownNumber.textContent = count;
            countdownOverlay.classList.add('active');
            
            // Play initial beep
            playBeep(660, 0.08);
            
            const interval = setInterval(() => {
                count--;
                if (count > 0) {
                    countdownNumber.textContent = count;
                    playBeep(660, 0.08);
                } else {
                    clearInterval(interval);
                    countdownOverlay.classList.remove('active');
                    
                    // Trigger flash visual effect
                    flashOverlay.classList.add('active');
                    // Play synthesized shutter camera click
                    playShutterSound();
                    
                    setTimeout(() => {
                        flashOverlay.classList.remove('active');
                    }, 80);
                    
                    // Grab current image frame
                    captureFrameToMemory();
                    captureStatus.classList.remove('active');
                    resolve();
                }
            }, 1000);
        });
    }

    // Capture the exact video frame
    function captureFrameToMemory() {
        const tempCanvas = document.createElement('canvas');
        const tempCtx = tempCanvas.getContext('2d');
        
        const vw = video.videoWidth || 640;
        const vh = video.videoHeight || 480;
        
        let targetW, targetH;
        let aspect;
        
        if (activeOrientation === 'portrait') {
            targetW = 450;
            targetH = 600;
            aspect = 3 / 4;
        } else {
            targetW = 600;
            targetH = 450;
            aspect = 4 / 3;
        }
        
        tempCanvas.width = targetW;
        tempCanvas.height = targetH;
        
        let sx, sy, sw, sh;
        const currentAspect = vw / vh;
        
        if (currentAspect > aspect) {
            // Video is wider than target aspect ratio (e.g. 16:9 webcam stream cropped to 4:3 or 3:4)
            sw = vh * aspect;
            sh = vh;
            sx = (vw - sw) / 2;
            sy = 0;
        } else {
            // Video is taller than target aspect ratio
            sw = vw;
            sh = vw / aspect;
            sx = 0;
            sy = (vh - sh) / 2;
        }
        
        tempCtx.drawImage(video, sx, sy, sw, sh, 0, 0, targetW, targetH);
        
        const dataUrl = tempCanvas.toDataURL('image/png');
        capturedFrames.push(dataUrl);
        
        // Create thumbnail indicator inside progress bar
        const thumb = document.createElement('div');
        thumb.className = 'mini-thumb active';
        thumb.style.backgroundImage = `url('${dataUrl}')`;
        miniProgress.appendChild(thumb);
        
        // Remove active highlights after a brief moment
        setTimeout(() => {
            document.querySelectorAll('.mini-thumb').forEach(t => t.classList.remove('active'));
        }, 1000);
    }

    // Helper to resolve standard or custom theme style properties
    function getActiveTheme() {
        if (activeThemeId === 'custom') {
            return {
                bgColor: customThemeConfig.bgColor,
                borderColor: customThemeConfig.borderColor,
                textColor: customThemeConfig.textColor,
                borderWidth: parseInt(customThemeConfig.borderWidth),
                bgImage: customThemeConfig.bgImage,
                imageType: customThemeConfig.imageType,
                font: `bold 28px "${customThemeConfig.fontFamily}", sans-serif`,
                drawOverlay: (ctx, width, height) => {
                    const overlayType = customThemeConfig.overlayType;
                    if (overlayType === 'retro') {
                        ctx.fillStyle = 'rgba(74, 59, 50, 0.03)';
                        ctx.fillRect(0, 0, width, height);
                        ctx.strokeStyle = 'rgba(74, 59, 50, 0.4)';
                        ctx.lineWidth = 1;
                        ctx.strokeRect(15, 15, width - 30, height - 30);
                    } else if (overlayType === 'pastel') {
                        ctx.fillStyle = customThemeConfig.borderColor;
                        const drawHeart = (x, y, size) => {
                            ctx.beginPath();
                            ctx.moveTo(x, y + size/4);
                            ctx.quadraticCurveTo(x, y, x + size/2, y);
                            ctx.quadraticCurveTo(x + size, y, x + size, y + size/3);
                            ctx.quadraticCurveTo(x + size, y + size*2/3, x + size/2, y + size);
                            ctx.quadraticCurveTo(x, y + size*2/3, x - size/2, y + size);
                            ctx.quadraticCurveTo(x - size, y + size*2/3, x - size, y + size/3);
                            ctx.quadraticCurveTo(x - size, y, x - size/2, y);
                            ctx.quadraticCurveTo(x, y, x, y + size/4);
                            ctx.closePath();
                            ctx.fill();
                        };
                        ctx.save();
                        ctx.globalAlpha = 0.55;
                        drawHeart(40, height - 90, 16);
                        drawHeart(width - 60, height - 90, 16);
                        drawHeart(width/2 - 120, height - 42, 12);
                        drawHeart(width/2 + 120, height - 42, 12);
                        ctx.restore();
                    } else if (overlayType === 'cyberpunk') {
                        ctx.strokeStyle = 'rgba(255, 0, 127, 0.15)';
                        ctx.lineWidth = 1;
                        for (let y = 0; y < height; y += 40) {
                            ctx.beginPath();
                            ctx.moveTo(0, y);
                            ctx.lineTo(width, y);
                            ctx.stroke();
                        }
                        ctx.shadowColor = customThemeConfig.borderColor;
                        ctx.shadowBlur = 15;
                        ctx.strokeStyle = customThemeConfig.borderColor;
                        ctx.lineWidth = 3;
                        ctx.strokeRect(10, 10, width - 20, height - 20);
                        ctx.shadowColor = customThemeConfig.textColor;
                        ctx.shadowBlur = 10;
                        ctx.strokeStyle = customThemeConfig.textColor;
                        ctx.lineWidth = 1;
                        ctx.strokeRect(5, 5, width - 10, height - 10);
                        ctx.shadowColor = 'transparent';
                        ctx.shadowBlur = 0;
                    } else if (overlayType === 'party') {
                        ctx.save();
                        const colors = [customThemeConfig.borderColor, customThemeConfig.textColor, '#ffffff', '#ffd700'];
                        for (let i = 0; i < 40; i++) {
                            const x = Math.random() * width;
                            const y = Math.random() * height;
                            const onMargin = x < 40 || x > (width - 40) || y < 40 || y > (height - 90);
                            if (onMargin) {
                                ctx.fillStyle = colors[Math.floor(Math.random() * colors.length)];
                                ctx.beginPath();
                                if (Math.random() > 0.4) {
                                    ctx.arc(x, y, Math.random() * 3 + 2, 0, Math.PI * 2);
                                } else {
                                    ctx.rect(x, y, Math.random() * 6 + 2, Math.random() * 6 + 2);
                                }
                                ctx.fill();
                            }
                        }
                        ctx.restore();
                    }
                }
            };
        }
        return themeStyles[activeThemeId] || themeStyles['retro'];
    }

    // Canvas drawing and stitching engine
    function generateFinalStrip() {
        if (capturedFrames.length === 0) return;
        
        const canvas = document.getElementById('hidden-canvas');
        const ctx = canvas.getContext('2d');
        const theme = getActiveTheme();
        const filter = filterStyles[activeFilter];
        const captionText = frameTextInput.value.toUpperCase();
        
        // Photo specs
        let photoW, photoH;
        if (activeOrientation === 'portrait') {
            photoW = 450;
            photoH = 600; // 3:4 Aspect ratio
        } else {
            photoW = 600;
            photoH = 450; // 4:3 Aspect ratio
        }
        
        // Frame/Margin specs
        const topMargin = 40;
        const sideMargin = 40;
        const photoGap = 30;
        const bottomAreaHeight = 100; // Text caption space
        
        let canvasW, canvasH;
        
        // Determine layout dimensions
        if (activeLayoutId === '2x2' && activeFramesCount === 4) {
            // Grid style: 2 columns x 2 rows
            canvasW = (photoW * 2) + (sideMargin * 2) + photoGap;
            canvasH = (photoH * 2) + topMargin + bottomAreaHeight + photoGap;
        } else {
            // Vertical strip layout: stacked photos
            canvasW = photoW + (sideMargin * 2);
            canvasH = (photoH * activeFramesCount) + topMargin + bottomAreaHeight + (photoGap * (activeFramesCount - 1));
        }
        
        canvas.width = canvasW;
        canvas.height = canvasH;
        
        // Load all required images asynchronously before rendering
        const imagesToLoad = [];
        capturedFrames.forEach((src, index) => {
            imagesToLoad.push({ type: 'photo', index: index, src: src });
        });
        
        let hasCustomImage = false;
        if (theme.bgImage) {
            hasCustomImage = true;
            imagesToLoad.push({ type: 'template', src: theme.bgImage });
        }
        
        let loadedCount = 0;
        const loadedPhotos = [];
        let loadedTemplateImg = null;
        
        imagesToLoad.forEach(item => {
            const img = new Image();
            img.src = item.src;
            img.onload = () => {
                loadedCount++;
                if (item.type === 'photo') {
                    loadedPhotos[item.index] = img;
                } else if (item.type === 'template') {
                    loadedTemplateImg = img;
                }
                
                if (loadedCount === imagesToLoad.length) {
                    drawAllLayers();
                }
            };
            img.onerror = () => {
                loadedCount++;
                if (loadedCount === imagesToLoad.length) {
                    drawAllLayers();
                }
            };
        });
        
        function drawAllLayers() {
            // LAYER 1: Draw solid background color
            ctx.fillStyle = theme.bgColor;
            ctx.fillRect(0, 0, canvasW, canvasH);
            
            // LAYER 2: Draw custom template image as Background
            if (hasCustomImage && loadedTemplateImg && theme.imageType === 'background') {
                ctx.drawImage(loadedTemplateImg, 0, 0, canvasW, canvasH);
            }
            
            // LAYER 3: Draw theme procedural overlay (grid, stickers, neon borders)
            if (theme.drawOverlay) {
                theme.drawOverlay(ctx, canvasW, canvasH);
            }
            
            // LAYER 4: Draw all captured photos (with their filters and photo frames)
            loadedPhotos.forEach((img, idx) => {
                if (!img) return;
                
                // Draw coordinate calculations
                let destX, destY;
                
                if (activeLayoutId === '2x2' && activeFramesCount === 4) {
                    // Grid Math
                    const col = idx % 2;
                    const row = Math.floor(idx / 2);
                    destX = sideMargin + (col * (photoW + photoGap));
                    destY = topMargin + (row * (photoH + photoGap));
                } else {
                    // Vertical Stack Math
                    destX = sideMargin;
                    destY = topMargin + (idx * (photoH + photoGap));
                }
                
                ctx.save();
                
                // Draw photo border/card background under the image first
                ctx.fillStyle = theme.borderColor;
                const borderOffset = theme.borderWidth;
                ctx.fillRect(
                    destX - borderOffset, 
                    destY - borderOffset, 
                    photoW + (borderOffset * 2), 
                    photoH + (borderOffset * 2)
                );
                
                // Apply filter exclusively to the image draw
                ctx.filter = filter;
                ctx.drawImage(img, destX, destY, photoW, photoH);
                
                ctx.restore();
            });
            
            // LAYER 5: Draw custom template image as Overlay Frame
            if (hasCustomImage && loadedTemplateImg && (!theme.imageType || theme.imageType === 'overlay')) {
                ctx.drawImage(loadedTemplateImg, 0, 0, canvasW, canvasH);
            }
            
            // LAYER 6: Draw Caption Text
            ctx.save();
            ctx.fillStyle = theme.textColor;
            ctx.textAlign = 'center';
            ctx.font = theme.font;
            
            const textX = canvasW / 2;
            const textY = canvasH - 45;
            ctx.fillText(captionText, textX, textY);
            ctx.restore();
            
            // Output canvas result as PNG DataURL to preview img tag
            const dataUrl = canvas.toDataURL('image/png');
            finalStripImg.src = dataUrl;
            printImage.src = dataUrl; // Prepare printing file source
        }
    }

    // Print functionality - Directly trigger print layout stylesheet
    btnPrint.addEventListener('click', () => {
        window.print();
    });

    // Download functionality - Create virtual anchor tag and trigger click
    btnDownload.addEventListener('click', () => {
        const dataUrl = finalStripImg.src;
        if (!dataUrl) return;
        
        const textSlug = frameTextInput.value.trim().toLowerCase().replace(/[^a-z0-9]+/g, '_');
        const filename = `expo_${textSlug || 'photo'}_${Date.now()}.png`;
        
        const link = document.createElement('a');
        link.href = dataUrl;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    // Retake / Reset photo session
    btnRetake.addEventListener('click', () => {
        if (confirm('Apakah Anda ingin membuang foto ini dan melakukan foto ulang?')) {
            capturedFrames = [];
            miniProgress.innerHTML = '';
            
            // Clear outputs
            finalStripImg.src = '';
            printImage.src = '';
            
            // Show start states
            resultPlaceholder.classList.add('active');
            stripPreviewWrapper.classList.remove('active');
            filterSection.style.display = 'none';
            resultActions.style.display = 'none';
        }
    });

    // Custom Theme configuration listener updates
    customBgColorInput.addEventListener('input', (e) => {
        customThemeConfig.bgColor = e.target.value;
        triggerCustomThemeUpdate();
    });
    customBorderColorInput.addEventListener('input', (e) => {
        customThemeConfig.borderColor = e.target.value;
        triggerCustomThemeUpdate();
    });
    customTextColorInput.addEventListener('input', (e) => {
        customThemeConfig.textColor = e.target.value;
        triggerCustomThemeUpdate();
    });
    customBorderWidthInput.addEventListener('input', (e) => {
        const val = e.target.value;
        customBorderWidthVal.textContent = `${val}px`;
        customThemeConfig.borderWidth = val;
        triggerCustomThemeUpdate();
    });
    customFontFamilySelect.addEventListener('change', (e) => {
        customThemeConfig.fontFamily = e.target.value;
        triggerCustomThemeUpdate();
    });
    customOverlayTypeSelect.addEventListener('change', (e) => {
        customThemeConfig.overlayType = e.target.value;
        triggerCustomThemeUpdate();
    });

    // Custom image upload listener
    if (customBgImageInput) {
        customBgImageInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;
            
            // Limit size to 5MB
            if (file.size > 5 * 1024 * 1024) {
                alert('File gambar terlalu besar. Maksimal 5MB.');
                customBgImageInput.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(evt) {
                const img = new Image();
                img.src = evt.target.result;
                img.onload = function() {
                    // Resize to max 1200px dimension to optimize session weight
                    const maxDim = 1200;
                    let w = img.width;
                    let h = img.height;
                    if (w > maxDim || h > maxDim) {
                        if (w > h) {
                            h = Math.round((h * maxDim) / w);
                            w = maxDim;
                        } else {
                            w = Math.round((w * maxDim) / h);
                            h = maxDim;
                        }
                    }
                    const canvas = document.createElement('canvas');
                    canvas.width = w;
                    canvas.height = h;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, w, h);
                    
                    const compressedDataUrl = canvas.toDataURL('image/png');
                    
                    customThemeConfig.bgImage = compressedDataUrl;
                    customImagePreview.src = compressedDataUrl;
                    customImagePreviewWrapper.style.display = 'block';
                    imageTypeGroup.style.display = 'block';
                    
                    triggerCustomThemeUpdate();
                };
            };
            reader.readAsDataURL(file);
        });
    }

    // Clear custom image listener
    if (btnClearBgImage) {
        btnClearBgImage.addEventListener('click', () => {
            customBgImageInput.value = '';
            customThemeConfig.bgImage = null;
            customImagePreview.src = '';
            customImagePreviewWrapper.style.display = 'none';
            imageTypeGroup.style.display = 'none';
            triggerCustomThemeUpdate();
        });
    }

    // Custom image application type listener
    if (customImageTypeSelect) {
        customImageTypeSelect.addEventListener('change', (e) => {
            customThemeConfig.imageType = e.target.value;
            triggerCustomThemeUpdate();
        });
    }

    function triggerCustomThemeUpdate() {
        if (activeThemeId === 'custom' && capturedFrames.length === activeFramesCount) {
            generateFinalStrip();
        }
    }

    // Save custom theme to session via AJAX
    const btnSaveCustomTheme = document.getElementById('btn-save-custom-theme');
    const customThemeNameInput = document.getElementById('custom-theme-name');
    
    if (btnSaveCustomTheme) {
        btnSaveCustomTheme.addEventListener('click', async () => {
            const name = customThemeNameInput.value.trim();
            if (!name) {
                alert('Silakan masukkan nama template terlebih dahulu.');
                customThemeNameInput.focus();
                return;
            }
            
            const payload = {
                name: name,
                bgColor: customThemeConfig.bgColor,
                borderColor: customThemeConfig.borderColor,
                textColor: customThemeConfig.textColor,
                borderWidth: customThemeConfig.borderWidth,
                fontFamily: customThemeConfig.fontFamily,
                overlayType: customThemeConfig.overlayType,
                bgImage: customThemeConfig.bgImage,
                imageType: customThemeConfig.imageType
            };
            
            try {
                btnSaveCustomTheme.setAttribute('disabled', 'true');
                btnSaveCustomTheme.innerHTML = '<i class="bi bi-hourglass-split"></i> Menyimpan...';
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/themes/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(payload)
                });
                
                const data = await response.json();
                if (data.success) {
                    alert('Template kustom berhasil disimpan ke Session!');
                    window.location.reload();
                } else {
                    alert('Gagal menyimpan template: ' + (data.message || 'Error tidak diketahui'));
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan saat menyimpan template.');
            } finally {
                btnSaveCustomTheme.removeAttribute('disabled');
                btnSaveCustomTheme.innerHTML = '<i class="bi bi-cloud-arrow-up-fill"></i> Simpan Template';
            }
        });
    }

    // Delete custom theme from session via AJAX
    const deleteThemeButtons = document.querySelectorAll('.btn-delete-custom-theme');
    deleteThemeButtons.forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.stopPropagation(); // Prevent choosing this card
            
            const themeId = btn.dataset.id;
            if (!confirm('Apakah Anda yakin ingin menghapus template kustom ini?')) {
                return;
            }
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch(`/themes/delete/${themeId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    alert('Template kustom berhasil dihapus!');
                    window.location.reload();
                } else {
                    alert('Gagal menghapus template: ' + (data.message || 'Error tidak diketahui'));
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan saat menghapus template.');
            }
        });
    });

    // Auto-start camera if page loaded
    startCamera();
});
