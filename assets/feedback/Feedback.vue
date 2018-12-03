<!-- assets/feedback/Feedback.vue -->
<template>
    <div>
        <reactions :session-id="sessionId"></reactions>
        <ul v-if="feedback.length" id="comments">
            <li v-for="f in feedback" :key="f['@id']">
                {{ f.author }}
                <star-rating :rating="f.rating" :read-only="true" :star-size="20"></star-rating>
                {{ f.comment }}
            </li>
        </ul>
        <p v-else id="no-comments">No feedback yet 🙁!</p>

        <p v-if="sent">Thanks for rating this talk!</p>
        <form v-else @submit.prevent="onSubmit">
            <input v-model="author" name="author" placeholder="Author">
            <star-rating v-model="rating" :star-size="20"></star-rating>
            <textarea v-model="comment" name="comment" placeholder="This talk was..."></textarea>

            <input :disabled="!author || !rating || !comment" type="submit" value="Post">
        </form>
    </div>
</template>

<script>
    import Reactions from "./Reactions";
    export default {
        components: {Reactions},
        props: {sessionId: {type: String, required: true}},
        data() {
            return {feedback: [], author: '', rating: 0, comment: '', sent: false};
        },
        created() {
            this.fetchFeedback();
        },
        methods: {
            fetchFeedback() {
                fetch(`/api/sessions/${this.sessionId}/feedback`)
                    .then(response => response.json())
                    .then(data => this.feedback = data['hydra:member']);
            },
            onSubmit() {
                const {sessionId, author, rating, comment} = this;
                fetch('/api/feedback', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/ld+json'},
                    body: JSON.stringify({session: `/api/sessions/${sessionId}`, author, rating, comment})
                })
                    .then(({ok, statusText}) => {
                        if (!ok) {
                            alert(statusText);
                            return;
                        }

                        this.sent = true;
                        this.fetchFeedback();
                    });
            }
        }
    }
</script>
