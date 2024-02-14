<script setup>
/**
 * @requires Modal - モーダルダイアログを表示するためのコンポーネント
 * @requires ref - リアクティブなデータ参照を作成するために使用
 * @requires computed - Vue 3の算出プロパティを作成するために使用
 */
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { computed, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
</script>

<template>
    <section class="text-gray-600 body-font relative">
        <div class="container px-5 py-24 mx-auto flex sm:flex-nowrap flex-wrap items-start">
            <div class="w-full lg:w-2/3 md:w-1/2 bg-dark p-10 rounded-lg mr-10">
                <!-- Timeline -->
                <div>
                    <!-- Heading -->
                    <div class="ps-2 my-2 first:mt-0">
                        <h3 class="text-base font-medium uppercase text-light">
                            1 Aug, 2023
                        </h3>
                    </div>
                    <!-- End Heading -->

                    <!-- Item -->
                    <div class="flex gap-x-3">
                        <!-- Icon -->
                        <div class="relative last:after:hidden after:absolute after:top-7 after:bottom-0 after:start-3.5 after:w-px after:-translate-x-[0.5px] after:bg-gray-200 dark:after:bg-gray-700">
                            <div class="relative z-10 size-7 flex justify-center items-center">
                                <span class="flex flex-shrink-0 justify-center items-center size-7 bg-white border border-gray-200 text-[10px] font-semibold uppercase text-gray-600 rounded-full dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">
                                    <svg class="flex-shrink-0 size-4 mt-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 3h5v5" />
                                        <path d="M8 3H3v5" />
                                        <path d="M12 22v-8.3a4 4 0 0 0-1.172-2.872L3 3" />
                                        <path d="m15 9 6-6" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <!-- End Icon -->

                        <!-- Right Content -->
                        <div class="grow pt-0.5 pb-8">
                            <h3 class="flex gap-x-1.5 font-semibold text-light">
                                管理者
                            </h3>
                            <p class="mt-1 text-gray-200">
                                Just chill for now... 😉
                            </p>
                        </div>
                        <!-- End Right Content -->
                    </div>
                    <!-- End Item -->

                    <!-- Item -->
                    <div class="flex gap-x-3">
                        <!-- Icon -->
                        <div class="relative last:after:hidden after:absolute after:top-7 after:bottom-0 after:start-3.5 after:w-px after:-translate-x-[0.5px] after:bg-gray-200 dark:after:bg-gray-700">
                            <div class="relative z-10 size-7 flex justify-center items-center">
                                <span class="flex flex-shrink-0 justify-center items-center size-7 bg-white border border-gray-200 text-[10px] font-semibold uppercase text-gray-600 rounded-full dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">
                                    <svg class="flex-shrink-0 size-4 mt-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 3h5v5" />
                                        <path d="M8 3H3v5" />
                                        <path d="M12 22v-8.3a4 4 0 0 0-1.172-2.872L3 3" />
                                        <path d="m15 9 6-6" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <!-- End Icon -->

                        <!-- Right Content -->
                        <div class="grow pt-0.5 pb-8">
                            <h3 class="flex gap-x-1.5 font-semibold text-light">
                                管理者
                            </h3>
                            <p class="mt-1 text-gray-200">
                                Just chill for now... 😉
                            </p>
                        </div>
                        <!-- End Right Content -->
                    </div>
                    <!-- End Item -->

                    <!-- Item -->
                    <div class="flex gap-x-3">
                        <!-- Icon -->
                        <div class="relative last:after:hidden after:absolute after:top-7 after:bottom-0 after:start-3.5 after:w-px after:-translate-x-[0.5px] after:bg-gray-200 dark:after:bg-gray-700">
                            <div class="relative z-10 size-7 flex justify-center items-center">
                                <span class="flex flex-shrink-0 justify-center items-center size-7 bg-white border border-gray-200 text-[10px] font-semibold uppercase text-gray-600 rounded-full dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">
                                    <svg class="flex-shrink-0 size-4 mt-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 3h5v5" />
                                        <path d="M8 3H3v5" />
                                        <path d="M12 22v-8.3a4 4 0 0 0-1.172-2.872L3 3" />
                                        <path d="m15 9 6-6" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <!-- End Icon -->

                        <!-- Right Content -->
                        <div class="grow pt-0.5 pb-8">
                            <h3 class="flex gap-x-1.5 font-semibold text-light">
                                管理者
                            </h3>
                            <p class="mt-1 text-gray-200">
                                Just chill for now... 😉
                            </p>
                        </div>
                        <!-- End Right Content -->
                    </div>
                    <!-- End Item -->

                    <!-- Heading -->
                    <div class="ps-2 my-2 first:mt-0">
                        <h3 class="text-base font-medium uppercase text-light">
                            31 Jul, 2023
                        </h3>
                    </div>
                    <!-- End Heading -->

                    <!-- Item -->
                    <div class="flex gap-x-3">
                        <!-- Icon -->
                        <div class="relative last:after:hidden after:absolute after:top-7 after:bottom-0 after:start-3.5 after:w-px after:-translate-x-[0.5px] after:bg-gray-200 dark:after:bg-gray-700">
                            <div class="relative z-10 size-7 flex justify-center items-center">
                                <span class="flex flex-shrink-0 justify-center items-center size-7 bg-white border border-gray-200 text-[10px] font-semibold uppercase text-gray-600 rounded-full dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">
                                    <svg class="flex-shrink-0 size-4 mt-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 3h5v5" />
                                        <path d="M8 3H3v5" />
                                        <path d="M12 22v-8.3a4 4 0 0 0-1.172-2.872L3 3" />
                                        <path d="m15 9 6-6" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <!-- End Icon -->

                        <!-- Right Content -->
                        <div class="grow pt-0.5 pb-8">
                            <h3 class="flex gap-x-1.5 font-semibold text-light">
                                管理者
                            </h3>
                            <p class="mt-1 text-gray-200">
                                Just chill for now... 😉
                            </p>
                        </div>
                        <!-- End Right Content -->
                    </div>
                    <!-- End Item -->
                </div>
                <!-- End Timeline -->
            </div>
            <div class="lg:w-1/3 md:w-1/2 bg-black flex flex-col md:ml-auto w-full mt-8 md:mt-0">
                <h2 class="text-gray-500 text-lg md:text-2xl mb-1 font-medium title-font">コメント</h2>
                <div class="relative mb-4">
                    <label for="message" class="leading-7 text-sm text-light">商品へのコメント</label>
                    <textarea id="message" name="message" class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                </div>
                <button class="text-light bg-primary border-0 py-2 px-6 focus:outline-none hover:bg-selected-button rounded text-lg mb-4">コメントを送信する</button>
                <p class="leading-relaxed text-sm">相手のことを考え丁寧なコメントを心がけましょう。不快な言葉遣いなどは利用制限や退会処分となることがあります。</p>
            </div>
        </div>
    </section>
</template>
