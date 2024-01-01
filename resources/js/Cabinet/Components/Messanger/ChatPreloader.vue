<template>
    <div class="preloader__wrapper">
    <div class="preloader__inner">
      <div class="loading">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>
  </div>
</template>


<script>
export default {
    name:"ChatPreloader"
}
</script>

<style scoped>

.preloader__wrapper {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  z-index: 3;
  left: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  background: rgba(255, 255, 255, 0.75);
}

.loading {
	width: 60px;
	height: 60px;
	position: absolute;
	top: 50%;
	left: 50%;
	margin: -30px 0 0 -30px;
	transform: rotate(45deg);
}

.loading div {
	width: 6px;
	height: 6px;
	border-radius: 100%;
    filter:blur(.5px);
	float: left;
	margin-bottom: 12px;
    background: black;
	animation: scaleDot 2s ease infinite;
}

.loading div:not(:nth-child(4n+4)) {
	margin-right: 12px;
}

.loading div:nth-child(1) {
	animation-delay: 0;
}

.loading div:nth-child(2),
.loading div:nth-child(5) {
	animation-delay: 0.1s;
}

.loading div:nth-child(3),
.loading div:nth-child(6),
.loading div:nth-child(9) {
	animation-delay: 0.2s;
}

.loading div:nth-child(4),
.loading div:nth-child(7),
.loading div:nth-child(10),
.loading div:nth-child(13) {
	animation-delay: 0.3s;
}

.loading div:nth-child(8),
.loading div:nth-child(11),
.loading div:nth-child(14) {
	animation-delay: 0.4s;
}

.loading div:nth-child(12),
.loading div:nth-child(15) {
	animation-delay: 0.5s;
}

.loading div:nth-child(16) {
	animation-delay: 0.6s;
}

@-moz-keyframes scaleDot {
	40% {
		transform: scale(1.3) translate(-2px, -2px);
	}

	80% {
		transform: scale(1);
	}

	100% {
		transform: scale(1);
	}
}

@-webkit-keyframes scaleDot {
	40% {
		transform: scale(1.3) translate(-2px, -2px);
	}

	80% {
		transform: scale(1);
	}

	100% {
		transform: scale(1);
	}
}

@-o-keyframes scaleDot {
	40% {
		transform: scale(1.3) translate(-2px, -2px);
	}

	80% {
		transform: scale(1);
	}

	100% {
		transform: scale(1);
	}
}

@keyframes scaleDot {
	40% {
		transform: scale(1.3) translate(-2px, -2px);
	}

	80% {
		transform: scale(1);
	}

	100% {
		transform: scale(1);
	}
}
.preloader__inner {
  color: black;
}
</style>
