<!-- assets/feedback/Feedback.vue -->
<template>
    <div>
        <ul v-if="feedback.length">
            <li v-for="f in feedback" :key="f['@id']">
                <star-rating :rating="f.rating" :read-only="true" :star-size="20"></star-rating>
                {{ f.comment }}
            </li>
        </ul>
        <p v-else>No feedback yet 🙁!</p>

        <p v-if="sent">Thanks for rating this talk!</p>
        <form v-else @submit.prevent="onSubmit">
            <input v-model="author" name="author" placeholder="Author">
            <star-rating v-model="rating" id="rating" :star-size="20"></star-rating>
            <textarea v-model="comment" name="comment" placeholder="This talk was..."></textarea>

            <input type="submit" :disabled="!author || !rating || !comment">
        </form>
    </div>
</template>

<script>
    import StarRating from 'vue-star-rating';

    export default {
        components: {StarRating},
        props: {sessionId: {type: String, required: true}},
        methods: {
            fetchFeedback() {
                fetch(`/api/sessions/${this.sessionId}/feedback`)
                    .then(response => response.json())
                    .then(data => this.feedback = data['hydra:member'])
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
                    })
                ;
            }
        },
        data() {
            return {feedback: [], author: '', rating: 0, comment: '', sent: false};
        },
        created() {
            this.fetchFeedback();
        }
    }
</script>
