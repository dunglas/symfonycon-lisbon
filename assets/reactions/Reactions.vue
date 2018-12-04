<!-- assets/reactions/Reactions.vue -->
<template>
    <div class="box reactions">
        <h3 class="title is-5">Live feedback</h3>
        <div class="level is-mobile">
            <div class="level-item has-text-centered">
                <div class="reaction hearts">
                    <a class="button icon is-medium" @click="addReaction('hearts')">
                        <i id="hearts" class="far fa-grin-hearts fa-2x"></i>
                    </a>
                    <p>{{ hearts }}</p>
                </div>
            </div>
            <div class="level-item has-text-centered">
                <div class="reaction stars">
                    <a class="button icon is-medium" @click="addReaction('stars')">
                        <i id="stars" class="far fa-grin-stars fa-2x"></i>
                    </a>
                    <p>{{ stars }}</p>
                </div>
            </div>
            <div class="level-item has-text-centered">
                <div class="reaction tears">
                    <a class="button icon is-medium" @click="addReaction('tears')">
                        <i id="tears" class="far fa-grin-tears fa-2x"></i>
                    </a>
                    <p>{{ tears }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import ReceivedReaction from './ReceivedReaction';

    export default {
        props: {sessionId: {type: String, required: true}},
        data() {
            return {
                stars: 0,
                hearts: 0,
                tears: 0,
                reactions: []
            };
        },
        created() {
            const RR = Vue.extend(ReceivedReaction);
            const vm = new RR();
            vm.$mount();
            document.body.appendChild(vm.$el);

            fetch(`/api/sessions/${this.sessionId}/reactions`)
                .then(response =>
                    // Extract the hub URL from the Link header, http://localhost:3000
                    response.json().then(data => ({
                        data,
                        hubUrl: response.headers.get('Link').match(/<([^>]+)>;\s+rel="[^"]*mercure[^"]*"/)[1]
                    }))
                )
                .then(({data, hubUrl}) => {
                    Object.entries(data).forEach(([type, nb]) => this[type] = nb);

                    const es = new EventSource(`${hubUrl}?topic=${document.location.origin}/api/reactions/{id}`);
                    es.onmessage = ({data}) => {
                        const type = JSON.parse(data).type;
                        ++this[type];
                        vm.displayReceivedReaction(type);
                    }
                })
        },
        methods: {
            addReaction(type) {
                fetch('/api/reactions', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/ld+json'},
                    body: JSON.stringify({session: `/api/sessions/${this.sessionId}`, type})
                })
                    .then(({ok, statusText}) => {
                        if (!ok) alert(statusText);
                    });
            }
        }
    }
</script>
