<?php
/**
 * Template Name: ET: PONG
 *
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();
$container = get_theme_mod('understrap_container_type');
?>

<canvas id="pongCanvas" width="800" height="400" style="background-color:black; position: absolute; left: 50%;max-width: 100%; top: 50%; transform: translateX(-50%) translateY(-50%)"></canvas>

<script>
var canvas = document.getElementById("pongCanvas");
var context = canvas.getContext("2d");

var ball = {
    x: canvas.width / 2,
    y: canvas.height / 2,
    dx: 2,
    dy: 2,
    radius: 10,
    color: "white"
};

var paddleHeight = 100;
var paddleWidth = 10;
var paddleSpeed = 2;
var paddleY = (canvas.height - paddleHeight) / 2;

function drawBall() {
    context.beginPath();
    context.arc(ball.x, ball.y, ball.radius, 0, Math.PI*2);
    context.fillStyle = ball.color;
    context.fill();
    context.closePath();
}

function drawPaddle() {
    context.beginPath();
    context.rect(canvas.width - paddleWidth, paddleY, paddleWidth, paddleHeight);
    context.fillStyle = "white";
    context.fill();
    context.closePath();
}

function draw() {
    context.clearRect(0, 0, canvas.width, canvas.height);
    drawBall();
    drawPaddle();

    if(ball.y + ball.dy < ball.radius || ball.y + ball.dy > canvas.height - ball.radius) {
        ball.dy = -ball.dy;
    }
    
    if(ball.x + ball.dx > canvas.width - ball.radius - paddleWidth) {
        if(ball.y > paddleY && ball.y < paddleY + paddleHeight) {
            ball.dx = -ball.dx;
        } 
        else {
            // Stop the game when ball hits right edge
            clearInterval(interval); 
            alert("Game Over");
            document.location.reload();
        }
    } 
    else if(ball.x + ball.dx < ball.radius) {
        ball.dx = -ball.dx;
    }

    ball.x += ball.dx;
    ball.y += ball.dy;

    if (rightPressed && paddleY < canvas.height - paddleHeight) {
        paddleY += paddleSpeed;
    }
    else if (leftPressed && paddleY > 0) {
        paddleY -= paddleSpeed;
    }
}

var rightPressed = false;
var leftPressed = false;

document.addEventListener("keydown", keyDownHandler, false);
document.addEventListener("keyup", keyUpHandler, false);

function keyDownHandler(e) {
    if(e.key == "Up" || e.key == "ArrowUp") {
        rightPressed = true;
    }
    else if(e.key == "Down" || e.key == "ArrowDown") {
        leftPressed = true;
    }
}

function keyUpHandler(e) {
    if(e.key == "Up" || e.key == "ArrowUp") {
        rightPressed = false;
    }
    else if(e.key == "Down" || e.key == "ArrowDown") {
        leftPressed = false;
    }
}

var interval = setInterval(draw, 10);
</script>





    <?php
        if( get_field('show_contact_footer') ) { 

            include('page-templates/footer.php');		

        } 
    ?>

	<script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/jquery.scrolla.min.js"></script>

    <script>
        jQuery('.animate').scrolla();
    </script>
    <link
		rel="stylesheet"
		href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"
	/>
	<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

	<script>
		var swiper = new Swiper(".swiperCustomers", {
			loop: false,
			autoplay: true,
			spaceBetween: 0,        
			pagination: {
				el: ".swiper-pagination",
				clickable: true,
			},
			keyboard: {
				enabled: true,
			},
			slidesPerView: 1,
		});

	</script>
    <script>

    </script>
	<?php get_footer(); ?>
</div>


