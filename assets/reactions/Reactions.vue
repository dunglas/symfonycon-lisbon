<!-- assets/reactions/Reactions.vue -->
<template>
    <div class="box reactions">
        <h3 class="title is-5">Live feedback</h3>
        <div class="level is-mobile">
        <div class="level-item has-text-centered">
            <div class="reaction heart">
                <a class="button icon is-medium" @click="addReaction('love')"><i id="love" class="far fa-grin-hearts fa-2x"></i></a>
                <p>{{love}}</p>
            </div>
        </div>
                <div class="level-item has-text-centered">
            <div class="reaction star">
                <a class="button icon is-medium" @click="addReaction('star')"><i id="star" class="far fa-grin-stars fa-2x"></i></a>
                <p>{{star}}</p>
            </div>
        </div>
                <div class="level-item has-text-centered">
            <div class="reaction tear">
                <a class="button icon is-medium" @click="addReaction('tear')"><i id="tear" class="far fa-grin-tears fa-2x"></i></a>
                <p>{{tear}}</p>
            </div>
        </div>
    </div>
    </div>
</template>

<script>
    const colors = {
        star: "#ed8746",
        love: "#e31f1b",
        tear: "#67d3bb"
    };
    const randomIntFromInterval = (min, max) => Math.floor(Math.random()*(max-min+1)+min);
    export default {
        props: {sessionId: {type: String, required: true}},
        data() {
            return {star: 0, love: 0, tear: 0};
        },
        created() {
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
                        this.addReactionAnimation(type);
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
                    .then(({ok, statusText}) => {if (!ok) alert(statusText);});
            },
            addReactionAnimation(type) {
                const base = document.getElementById(type);
                const cln = base.cloneNode(true);
                cln.style.position = 'fixed';
                cln.style.color = colors[type];
                const left = randomIntFromInterval(40, 60);
                const rotate = randomIntFromInterval(-30, 30);
                cln.style.transform = `rotate(${rotate}deg)`;
                cln.style.left = left+"%";
                cln.classList.add("animated");
                document.body.appendChild(cln);
                setTimeout(() => cln.remove(), 2000);
            },
        }
    }
</script>
