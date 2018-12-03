<!-- assets/feedback/Feedback.vue -->
<template>
    <div class="container comments is-bordered">
            <h3 class="title is-4">Comments</h3>
            <div class="comments-wrapper" v-if="feedback.length" id="comments">
                <article class="media" v-for="f in feedback" :key="f['@id']">
                    <div class="media-left">
                        <div class="icon is-large"><i class="fas fa-user fa-2x"></i></div>
                    </div>
                    <div class="media-content">
                        <div class="content">
                            <div class="columns is-mobile">
                                <div class="column">
                                    <strong class="title is-5 is-marginless">{{ f.author }}</strong>
                                    <p>{{ f.comment }}</p>
                                </div>
                               <div class="column is-narrow">
                                <star-rating text-class="star-txt" active-color="#e31f1b" :rating="f.rating" :read-only="true" :star-size="15"></star-rating>
                               </div>
                            </div>
                            
                        </div>
                    </div>
                </article>
            </div>
            <div class="notification has-text-grey-light has-background-light" v-else id="no-comments">
                <div class="icon is-large"><i class="far fa-sad-tear"></i></div><span>No feedback yet !</span></div>

            <div class="notification" v-if="sent">Thanks for rating this talk!</div>
            <form v-else @submit.prevent="onSubmit" class="form has-background-light">
                <article class="media">
                <div class="media-left">
                    <div class="icon is-large"><i class="fas fa-comment-dots fa-2x"></i></div>
                </div>
                <div class="media-content">
                    <div class="field">
                        <input class="input" v-model="author" name="author" placeholder="Author">
                    </div>
                    <star-rating text-class="star-txt" active-color="#e31f1b" v-model="rating" :star-size="25"></star-rating>
                    <div class="field">
                        <p class="control">
                            <textarea class="textarea" v-model="comment" name="comment" placeholder="This talk was..."></textarea>
                        </p>
                    </div>
                    <nav class="level">
                    <div class="level-left">
                        <div class="level-item">
                            <input class="button is-primary" :disabled="!author || !rating || !comment" type="submit" value="Post">
                        </div>
                    </div>
                    </nav>
                </div>
                </article>
            </form>
    </div>
</template>

<script>
    export default {
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
