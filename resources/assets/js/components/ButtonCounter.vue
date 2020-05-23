<template>
<div id="button-counter">
	<button v-on:click="count++">You clicked me {{ count }} times.</button>
</div>
</template>

<script>
	export default {
		name:"button-counter",
		 data: function () {
                  return {
                        count: 0
                  }
            },		
	}
</script>

<style>
	.vbutton {
		display: flex;
		
		width: 100%;
		padding: 30upx 95upx;
		background: #ffffff;

	}

	.vbutton button {
		display: block;
		width: 560upx;
		height: 68upx;
		font-size: 28upx;
		line-height: 68upx;
		background: linear-gradient(to right, #ff4e33, #ff6933);
		color: #ffffff;
		border-radius: 68upx;
		margin: 0;
		box-shadow: 0upx 6upx 28upx 0upx rgba(242, 32, 0, 0.26);
	}

	.vbutton button:after {
		border: none;
	}
</style>
