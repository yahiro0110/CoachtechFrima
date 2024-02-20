<script setup>
/**
 * @requires AuthenticatedLayout - 認証済みユーザー向けのレイアウトコンポーネント
 * @requires StoreItemImagesForm - 商品画像をアップロードするためのフォームコンポーネント
 * @requires StoreItemDetailForm - 商品の詳細情報を入力するためのフォームコンポーネント
 * @requires PrimaryButton - プライマリボタンを表示するためのコンポーネント
 * @requires Head - Inertia.jsのヘルパーメソッドを提供し、ページのタイトルやメタ情報を動的に変更する
 * @requires useForm - Inertia.jsのフォームハンドリング機能を提供し、フォームの状態管理や送信時の処理を容易にする
 */
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { computed } from 'vue';

/**
 * コンポーネントのプロパティ定義。
 *
 * @prop {Object} purchase - 購入情報を含むオブジェクト
 */
const props = defineProps({
    purchase: Object,
});

/**
 * 出品者の画像アイコンを取得するための算出プロパティ。
 * 出品者の画像アイコンが存在する場合は、画像のパスを返し、存在しない場合はデフォルトのアイコンを返す。
 *
 * @returns {String} - 出品者のアイコンのパス
 */
const sellerIcon = computed(() => {
    return props.purchase.item.user.user_image.image_path ? '/storage/images/users/' + props.purchase.item.user.user_image.image_path : '/images/default-user-icon.jpg';
});

/**
 * 価格のフォーマットを提供する算出プロパティ。
 */
const formattedPrices = computed(() => {
    if (!props.purchase.item.price) return '---';
    return props.purchase.item.price.toLocaleString();
});

/**
 * 購入日をフォーマットする算出プロパティ。
 */
const formatDate = computed(() => {
    return new Date(props.purchase.created_at).toISOString().split('T')[0];
});
</script>

<template>
    <Head title="購入" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col justify-center items-center">
                <Link class="inline-flex items-center lg:mb-0 cursor-pointer" as="button" :href="`/seller?id=${purchase.item.seller_id}`">
                <img class="inline-block h-20 w-20 rounded-full object-cover" :src="sellerIcon" alt="seller image">
                </Link>
                <h2 class="font-great-vibes text-xl md:text-3xl text-light leading-tight text-center mt-4">Thank you for your purchase!</h2>
                <p class="text-gray-400 mt-8">あなたの購入が無事に完了しました!<br />この度はCOACHTECH FREMAをご利用いただき、誠にありがとうございます!</p>
            </div>
        </template>

        <section class="text-gray-600 body-font">
            <div class="container px-5 py-10 mx-auto w-full xl:flex xl:flex-col xl:items-center">

                <!-- 購入明細 -->
                <div class="xl:w-3/5 md:w-full mx-2 md:mx-0 md:py-6 mb-10">
                    <h1 class="text-lg md:text-2xl font-medium title-font text-gray-500 mb-4">購入内容</h1>

                    <div class="p-5 bg-dark rounded-lg w-full">
                        <div class="md:flex md:items-baseline">
                            <p class="flex-1 leading-relaxed text-light font-bold mb-1 md:mb-2">商品名</p>
                            <p class="flex-1 leading-relaxed text-light mb-3 md:mb-0">{{ purchase.item.name }}</p>
                        </div>

                        <div class="md:flex md:items-baseline">
                            <p class="flex-1 leading-relaxed text-light font-bold mb-1 md:mb-2">価格</p>
                            <p class="flex-1 leading-relaxed text-light mb-3 md:mb-0">{{ formattedPrices }}円</p>
                        </div>

                        <div class="md:flex md:items-baseline">
                            <p class="flex-1 leading-relaxed text-light font-bold mb-1 md:mb-2">ご注文日</p>
                            <p class="flex-1 leading-relaxed text-light mb-3 md:mb-0">{{ formatDate }}</p>
                        </div>

                        <div class="md:flex md:items-baseline">
                            <p class="flex-1 leading-relaxed text-light font-bold mb-1 md:mb-2">配送先</p>
                            <p class="flex-1 leading-relaxed text-light mb-3 md:mb-0">〒{{ purchase.ship_address }}</p>
                        </div>

                        <div class="md:flex md:items-baseline">
                            <p class="flex-1 leading-relaxed text-light font-bold">お支払い方法</p>
                            <p class="flex-1 leading-relaxed text-light mb-3 md:mb-0">{{ purchase.payment.name }}</p>
                        </div>
                    </div>
                </div>

                <!-- 次のステップに関する説明 -->
                <div class="xl:w-3/5 md:w-full mx-2 md:mx-0 md:py-6 mb-10">
                    <h1 class="text-lg md:text-2xl font-medium title-font text-gray-500 mb-4">次のステップ</h1>
                    <div class="flex relative pb-8">
                        <div class="h-full w-10 absolute inset-0 flex items-center justify-center -ml-1">
                            <div class="h-full w-0.5 bg-primary pointer-events-none"></div>
                        </div>
                        <div class="flex-shrink-0 w-5 h-5 rounded-full bg-primary inline-flex items-center justify-center text-white relative z-10 p-4">
                            <span>
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </span>
                        </div>
                        <div class="flex-grow ml-3">
                            <h2 class="font-medium title-font text-sm text-light mb-1 tracking-wider">STEP 1</h2>
                            <p class="leading-relaxed text-gray-400">出品者が商品の発送準備を行います。<br />発送完了次第、追跡情報をメールまたはアプリ内でお知らせいたします。</p>
                        </div>
                    </div>

                    <div class="flex relative pb-8">
                        <div class="h-full w-10 absolute inset-0 flex items-center justify-center -ml-1">
                            <div class="h-full w-0.5 bg-primary pointer-events-none"></div>
                        </div>
                        <div class="flex-shrink-0 w-5 h-5 rounded-full bg-primary inline-flex items-center justify-center text-white relative z-10 p-4">
                            <span>
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path>
                                    <path d="M22 4L12 14.01l-3-3"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="flex-grow ml-3">
                            <h2 class="font-medium title-font text-sm text-light mb-1 tracking-wider">STEP 2</h2>
                            <p class="leading-relaxed text-gray-400">商品受け取り後、状態に問題がなければ、「受け取り完了」のお手続きをおこなってください。<span class="hidden md:inline"><br />&nbsp;</span></p>
                        </div>
                    </div>

                    <div class="flex relative pb-8">
                        <div class="h-full w-10 absolute inset-0 flex items-center justify-center -ml-1">
                            <div class="h-full w-0.5 bg-primary pointer-events-none"></div>
                        </div>
                        <div class="flex-shrink-0 w-5 h-5 rounded-full bg-primary inline-flex items-center justify-center text-white relative z-10 p-4">
                            <span>
                                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                    <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="flex-grow ml-3">
                            <h2 class="font-medium title-font text-sm text-light mb-1 tracking-wider">STEP 3</h2>
                            <p class="leading-relaxed text-gray-400">商品や取引に満足されましたら、出品者への評価をお願いいたします。<br />あなたのフィードバックがフリマアプリコミュニティの品質向上に繋がります。</p>
                        </div>
                    </div>
                </div>

                <!-- サポートセンターの案内 -->
                <div class="xl:w-3/5 md:w-full mx-2 md:mx-0">
                    <h1 class="text-lg md:text-2xl font-medium title-font text-gray-500 mb-4">ご不明点やサポートが必要な場合</h1>
                    <p class="leading-relaxed text-gray-400">ご質問やご不明点がございましたら、いつでもCOACHTECH FREMAサポートセンターまでお気軽にお問い合わせください。<br />お客様の満足なお買い物体験をサポートするため、私たちは常にここにいます。</p>
                </div>

            </div>

        </section>

    </AuthenticatedLayout>
</template>
