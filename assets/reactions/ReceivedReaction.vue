<template>
    <div>
        <template v-for="(item, index) in reactions">
            <i :class="['far', `fa-grin-${item.type}`, 'fa-2x', 'animated']" :style="{left: `${item.left}%`, transform: `rotate(${item.rotate}deg)` }" :key="index"></i>
        </template>
    </div>
</template>

<script>
    const randomIntFromInterval = (min, max) => Math.floor(Math.random() * (max - min + 1) + min);

    export default {
        data() {
            return {
                count: 0,
                reactions: {}
            }
        },
        methods: {
            displayReceivedReaction(type) {
                const i = ++this.count;
                const obj = {
                    type,
                    left: randomIntFromInterval(40, 60),
                    rotate: randomIntFromInterval(-30, 30)
                };

                this.reactions[i] = obj;
                this.$forceUpdate();
                setTimeout(() => {
                    delete this.reactions[i];
                    this.$forceUpdate();
                }, 2000);
            }
        }
    }
</script>
