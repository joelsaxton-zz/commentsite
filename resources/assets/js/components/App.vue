<template>
    <div id="myApplication">
        <h1>Game of Thrones Comment Site</h1>
        <ul>
            <li v-for="(comment1, index1) of comments" :key="index1">
                {{ comment1.user.first_name + ' ' + comment1.user.last_name}}: {{ comment1.comment_text }}
                <ul>
                    <li v-for="(comment2, index2) of comment1.replies" :key="index2">
                        {{ comment2.user.first_name + ' ' + comment2.user.last_name}}: {{ comment2.comment_text }}
                        <ul>
                            <li v-for="(comment3, index3) of comment2.replies" :key="index3">
                                {{ comment3.user.first_name + ' ' + comment3.user.last_name}}: {{ comment3.comment_text }}
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
        <button @click="loadData()">Load Data</button>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: 'app',
        data: () => ({
            comments: []
        }),
        mounted() {
            //this.loadData()
        },
        methods: {
            loadData() {
                axios.get('api.json').then(response => {
                    console.log(response);
                    const {comments} = response.data;
                    console.log(comments);
                    // empty previous records
                    this.comments.length = 0;
                    // add new messages
                    this.comments.push(...comments);
                })
            }
        }
    }
</script>

<style lang="scss">
    #myApplication {
        text-align: center;
        color: #2c3e50;
        margin-top: 60px;
        p {
            color: red;
        }
    }
</style>