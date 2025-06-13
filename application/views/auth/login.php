<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - MONITORING FIKOM HOTSPOT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
            position: relative;
        }

        #particles-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 35px 70px rgba(0, 0, 0, 0.4);
        }

        .login-title {
            color: #ffffff;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .form-label {
            color: #ffffff;
            font-weight: 500;
            text-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);
        }

        .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            padding: 12px 15px;
            color: #ffffff;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.25);
            border-color: #00ffc3;
            box-shadow: 0 0 20px rgba(0, 255, 195, 0.3);
            color: #ffffff;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .cta-button {
            background: linear-gradient(45deg, #00bfa5, #00ffc3);
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            font-size: 18px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            width: 100%;
            color: #000;
            font-weight: bold;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 255, 195, 0.3);
        }

        .alert {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.5);
            color: #ffffff;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #00ffc3, #1de9b6);
            border-radius: 15px;
            display: inline-block;
            margin-bottom: 15px;
            position: relative;
            animation: pulse 2s infinite;
        }

        .logo-icon::before {
            content: '🌐';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 255, 195, 0.7);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(0, 255, 195, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(0, 255, 195, 0);
            }
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 30px 20px;
            }
            
            .login-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <canvas id="particles-canvas"></canvas>

    <div class="login-container">
        <div class="login-card">
            <div class="logo-section">
                <div class="logo-icon"></div>
            </div>
            
            <h2 class="login-title">MONITORING FIKOM HOTSPOT</h2>
            
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger mb-4">
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>
            
            <form action="<?php echo site_url('auth/login'); ?>" method="post">
                <div class="mb-4">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                </div>
                
                <button type="submit" class="cta-button">Login</button>
            </form>
        </div>
    </div>

    <script>
        class ParticleSystem {
            constructor() {
                this.canvas = document.getElementById('particles-canvas');
                this.ctx = this.canvas.getContext('2d');
                this.particles = [];
                this.mouse = { x: 0, y: 0 };
                this.init();
                this.createParticles();
                this.bindEvents();
                this.animate();
            }

            init() {
                this.canvas.width = window.innerWidth;
                this.canvas.height = window.innerHeight;
            }

            createParticles() {
                const numParticles = Math.floor((this.canvas.width * this.canvas.height) / 5000);
                for (let i = 0; i < numParticles; i++) {
                    this.particles.push({
                        x: Math.random() * this.canvas.width,
                        y: Math.random() * this.canvas.height,
                        vx: (Math.random() - 0.5) * 0.8,
                        vy: (Math.random() - 0.5) * 0.8,
                        size: Math.random() * 3 + 0.5,
                        opacity: Math.random() * 0.9 + 0.1,
                        originalVx: (Math.random() - 0.5) * 0.8,
                        originalVy: (Math.random() - 0.5) * 0.8
                    });
                }
            }

            bindEvents() {
                window.addEventListener('resize', () => {
                    this.canvas.width = window.innerWidth;
                    this.canvas.height = window.innerHeight;
                });

                window.addEventListener('mousemove', (e) => {
                    this.mouse.x = e.clientX;
                    this.mouse.y = e.clientY;
                });
            }

            updateParticles() {
                this.particles.forEach(particle => {
                    const dx = this.mouse.x - particle.x;
                    const dy = this.mouse.y - particle.y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    const repulsionRadius = 180;

                    if (distance < repulsionRadius) {
                        const force = (repulsionRadius - distance) / repulsionRadius;
                        const angle = Math.atan2(dy, dx);
                        particle.vx -= Math.cos(angle) * force * 0.8;
                        particle.vy -= Math.sin(angle) * force * 0.8;
                    }

                    particle.vx += (particle.originalVx - particle.vx) * 0.02;
                    particle.vy += (particle.originalVy - particle.vy) * 0.02;

                    particle.x += particle.vx;
                    particle.y += particle.vy;

                    if (particle.x < 0 || particle.x > this.canvas.width) {
                        particle.vx *= -1;
                        particle.originalVx *= -1;
                    }
                    if (particle.y < 0 || particle.y > this.canvas.height) {
                        particle.vy *= -1;
                        particle.originalVy *= -1;
                    }

                    particle.x = Math.max(0, Math.min(this.canvas.width, particle.x));
                    particle.y = Math.max(0, Math.min(this.canvas.height, particle.y));
                });
            }

            drawConnections() {
                for (let i = 0; i < this.particles.length; i++) {
                    for (let j = i + 1; j < this.particles.length; j++) {
                        const dx = this.particles[i].x - this.particles[j].x;
                        const dy = this.particles[i].y - this.particles[j].y;
                        const distance = Math.sqrt(dx * dx + dy * dy);
                        if (distance < 150) {
                            const opacity = (150 - distance) / 150 * 0.4;
                            this.ctx.beginPath();
                            this.ctx.strokeStyle = `rgba(0, 255, 195, ${opacity})`;
                            this.ctx.lineWidth = 1;
                            this.ctx.moveTo(this.particles[i].x, this.particles[i].y);
                            this.ctx.lineTo(this.particles[j].x, this.particles[j].y);
                            this.ctx.stroke();
                        }
                    }
                }
            }

            drawParticles() {
                this.particles.forEach(particle => {
                    const gradient = this.ctx.createRadialGradient(
                        particle.x, particle.y, 0,
                        particle.x, particle.y, particle.size * 2
                    );
                    gradient.addColorStop(0, `rgba(0, 255, 195, ${particle.opacity})`);
                    gradient.addColorStop(0.5, `rgba(0, 255, 195, ${particle.opacity * 0.6})`);
                    gradient.addColorStop(1, `rgba(0, 255, 195, 0)`);

                    this.ctx.beginPath();
                    this.ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
                    this.ctx.fillStyle = gradient;
                    this.ctx.fill();

                    this.ctx.beginPath();
                    this.ctx.arc(particle.x, particle.y, particle.size * 0.3, 0, Math.PI * 2);
                    this.ctx.fillStyle = `rgba(0, 255, 195, ${particle.opacity})`;
                    this.ctx.fill();
                });
            }

            animate() {
                this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                this.updateParticles();
                this.drawConnections();
                this.drawParticles();
                requestAnimationFrame(() => this.animate());
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            new ParticleSystem();
        });
    </script>
</body>
</html>