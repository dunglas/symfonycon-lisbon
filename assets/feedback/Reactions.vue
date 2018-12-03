<!-- assets/feedback/Reaction.vue -->
<template>
    <ul>
        <li><a @click="addReaction('thumb')">👍</a> ({{ thumb }})</li>
        <li><a @click="addReaction('love')">❤️</a>️ ({{ love }})</li>
        <li><a @click="addReaction('clap')">👏️</a> ({{ clap }})</li>
    </ul>
</template>

<script>
    export default {
        props: {sessionId: {type: String, required: true}},
        data() {
            return {thumb: 0, love: 0, clap: 0};
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
                    es.onmessage = ({data}) => ++this[JSON.parse(data).type];
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
            }
        }
    }
</script>
