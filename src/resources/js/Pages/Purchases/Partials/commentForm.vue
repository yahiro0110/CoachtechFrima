<script setup>
/**
 * @requires Modal - モーダルダイアログを表示するためのコンポーネント
 * @requires ref - リアクティブなデータ参照を作成するために使用
 * @requires computed - Vue 3の算出プロパティを作成するために使用
 */
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { computed, onMounted, ref } from 'vue';
import { useForm, usePage } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

/**
 * コンポーネントのプロパティ定義。
 *
 * @property {Object} item - 商品の情報を含むオブジェクト(商品画像を含む)
 * @property {Array} comments - 商品に対するコメントのリスト
 */
const props = defineProps({
    item: Object,
    comments: Array,
});

/**
 * コメント入力フォームのデータモデル。
 *
 * @type {Object} form - コメントの登録に必要なデータを保持するフォームオブジェクト
 * @property {number} item_id - 商品のID
 * @property {number} user_id - ユーザーのID
 * @property {string} message - コメントの内容
 */
const form = useForm({
    item_id: props.item.id,
    user_id: usePage().props.value.auth.user.id,
    message: null,
});

/**
 * コメントを日付ごとにグループ化するためのリアクティブなプロパティ。
 *
 * @type {Object} groupedComments - 日付ごとにグループ化されたコメントのリスト
 */
const groupedComments = ref({});

/**
 * コメントを日付ごとにグループ化する関数。
 *
 * @returns {Object} result - 日付ごとにグループ化されたコメントのリスト
 */
const groupCommentsByDate = () => {
    const result = props.comments.reduce((acc, comment) => {
        // ISO形式の日付から"YYYY-MM-DD"形式へ変換
        const date = new Date(comment.created_at).toISOString().split('T')[0];
        if (!acc[date]) {
            acc[date] = [];
        }
        acc[date].push({
            id: comment.id,
            userName: comment.user.name,
            userIcon: comment.user.user_image.image_path ? '/storage/images/users/' + comment.user.user_image.image_path : '/images/default-user-icon.jpg',
            message: formattedMessage(comment.message),
        });
        return acc;
    }, {});

    groupedComments.value = result;
};

// コンポーネントがマウントされたときにコメントを日付ごとにグループ化する
onMounted(groupCommentsByDate);

/**
 * メッセージをHTML形式にフォーマットする関数。
 *
 * @param {string} message - フォーマットするメッセージ
 * @returns {string} formattedMessage - フォーマットされたメッセージ
 */
const formattedMessage = (message) => message.replace(/\n/g, '<br>');

/**
 * コメントを登録する関数。
 *
 * Inertia.jsのpostメソッドを使用して、フォームに入力されたデータをサーバーに送信する。
 * 成功時にはフォームのメッセージ内容を初期化し、コメントを日付ごとに再度グループ化する。
 */
const storeCommentInformation = () => {
    form.post(route('comments.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.message = null;
            groupCommentsByDate();
        },
    });
};

/**
 * コメントを削除する関数。
 *
 * Inertia.jsのdeleteメソッドを使用して、指定されたIDのコメントをサーバーから削除する。
 * 成功時にはコメントを日付ごとに再度グループ化する。
 *
 * @param {number} id - 削除するコメントのID
 */
const deleteCommentInformation = (id) => {
    Inertia.delete(route('comments.destroy', { comment: id }), {
        preserveScroll: true,
        onSuccess: () => {
            groupCommentsByDate();
        },
    });
};
</script>

<template>
    <section class="text-gray-600 body-font relative">
        <div class="container px-5 py-24 mx-auto flex sm:flex-nowrap flex-wrap items-start">
            <div class="w-full lg:w-2/3 md:w-1/2 bg-black  md:mr-10 mx-auto">
                <h2 class="text-gray-500 text-lg md:text-2xl mb-3 font-medium title-font">コメント({{ comments.length }})</h2>
                <!-- Timeline -->
                <div class="bg-dark p-10 rounded-lg" v-show="comments.length">
                    <!-- Heading -->
                    <div v-for="(commentList, date) in groupedComments" :key="date">
                        <div class="my-2 first:mt-0">
                            <h3 class="text-base font-medium uppercase text-light">
                                {{ date }}
                            </h3>
                        </div>
                        <!-- End Heading -->

                        <!-- Item -->
                        <div class="flex gap-x-3" v-for="comment in commentList" :key="comment.message">
                            <!-- Icon -->
                            <div class="relative last:after:hidden after:absolute after:top-7 after:bottom-0 after:start-4 md:after:start-5 after:w-px after:-translate-x-[0.5px] after:bg-gray-700">
                                <div class="relative z-10 size-7 flex justify-center items-center">
                                    <span class="flex flex-shrink-0 justify-center items-center size-7 b-1 text-[10px] font-semibold uppercase rounded-full">
                                        <img class="inline-block h-[2.5rem] w-[2.5rem] rounded-full object-cover" :src="comment.userIcon" alt="seller image">
                                    </span>
                                </div>
                            </div>
                            <!-- End Icon -->

                            <!-- Right Content -->
                            <div class="grow pt-0.5 pb-8 w-full">
                                <h3 class="flex gap-x-1.5 font-semibold text-light">
                                    {{ comment.userName }}
                                </h3>
                                <p class="mt-1 text-gray-200" v-html="comment.message"></p>
                                <a class="text-xs hover:bg-red-600 hover:text-light rounded-3xl p-1 cursor-pointer" @click.prevent="deleteCommentInformation(comment.id)">削除</a>
                            </div>
                            <!-- End Right Content -->
                        </div>
                        <!-- End Item -->
                    </div>

                </div>
                <!-- End Timeline -->
            </div>
            <div class="lg:w-1/3 md:w-1/2 bg-black flex flex-col md:ml-auto w-full mt-9">
                <form @submit.prevent="storeCommentInformation">
                    <div class="relative mb-4">
                        <label for="message" class="leading-7 text-sm text-light">商品へのコメント</label>
                        <textarea id="message" name="message" class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out" v-model="form.message"></textarea>
                    </div>
                    <button class="text-light bg-primary border-0 py-2 px-6 focus:outline-none hover:bg-selected-button rounded text-lg mb-4 w-full">コメントを送信する</button>
                    <p class="leading-relaxed text-sm">相手のことを考え丁寧なコメントを心がけましょう。不快な言葉遣いなどは利用制限や退会処分となることがあります。</p>
                </form>
            </div>
        </div>
    </section>
</template>
