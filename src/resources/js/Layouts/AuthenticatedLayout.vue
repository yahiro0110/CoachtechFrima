<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia'

const showingNavigationDropdown = ref(false);

// ナビゲーション関数
const navigateToCreateItem = () => {
    Inertia.visit(route('items.create'));
}

/**
 * プロフィール画像のプレビュー用のURLを提供するリアクティブなプロパティ。
 *
 *  @type {string} - プロフィール画像のファイルパス
 */
let userIcon = usePage().props.value.auth.userImage ? ref('/storage/images/users/' + usePage().props.value.auth.userImage) : ref('/images/default-user-icon.jpg');
</script>

<template>
    <div class="min-h-screen bg-black">

        <!-- Page Heading -->
        <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center sticky top-0 z-50 bg-black opacity-90">
            <a class="flex title-font font-medium items-center text-gray-900 mb-4 md:mb-0">
                <!-- Logo -->
                <Link :href="route('home')">
                <div class="shrink-0 flex items-center">
                    <ApplicationLogo class="block h-9 w-auto fill-current text-light" />
                    <span class="font-playfair-display ml-3 text-xl text-light">COACHTECH FREMA</span>
                </div>
                </Link>
                <!-- アカウントボタン レスポンシブ対応（スマホサイズで表示） -->
                <div class="md:hidden sm:flex sm:items-center sm:ml-6">
                    <!-- Settings Dropdown -->
                    <div class="ml-3 relative">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <img class="inline-block h-[3rem] w-[3rem] rounded-full object-cover m-2" :src="userIcon" alt="Image Description">
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')"> プロフィール設定 </DropdownLink>
                                <DropdownLink :href="route('items.create')"> 出品する </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button"> ログアウト </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </a>
            <nav class="md:ml-auto flex flex-wrap items-center text-base justify-center">
                <!-- アカウントボタン（タブレット以上で表示）  -->
                <div class="hidden md:flex md:items-center md:ml-6">
                    <!-- Settings Dropdown -->
                    <div class="ml-3 relative">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <img class="inline-block h-[3.75rem] md:h-[2rem] w-[3.75rem] md:w-[2rem] rounded-full object-cover m-2" :src="userIcon" alt="Image Description">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center mr-5 border border-transparent text-lg md:text-sm leading-4 font-medium rounded-md text-light focus:outline-none transition ease-in-out duration-150">
                                        {{ $page.props.auth.user.name }}
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')"> プロフィール設定 </DropdownLink>
                                <DropdownLink :href="route('items.index')"> 出品履歴 </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button"> ログアウト </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </nav>

            <!-- 出品ボタン（タブレット以上で表示） -->
            <button class="hidden md:inline-flex items-center text-light bg-primary font-bold border-0 py-1 px-3 focus:outline-none hover:bg-selected-button rounded text-base mt-4 md:mt-0" @click="navigateToCreateItem">出品</button>
        </div>

        <!-- Page Heading -->
        <header class="bg-black" v-if="$slots.header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Page Content -->
        <main>
            <slot />
        </main>

        <footer class="text-gray-600 body-font sticky top-full">
            <div class="container px-5 py-24 mx-auto">
                <div class="flex flex-wrap md:justify-between md:text-left text-center order-first">
                    <div class="lg:w-1/4 md:w-1/2 w-full px-4">
                        <h2 class="title-font font-medium text-light tracking-widest text-sm mb-3">CATEGORIES</h2>
                        <nav class="list-none mb-10">
                            <li>
                                <a class="text-gray-600 hover:text-gray-800">First Link</a>
                            </li>
                            <li>
                                <a class="text-gray-600 hover:text-gray-800">Second Link</a>
                            </li>
                            <li>
                                <a class="text-gray-600 hover:text-gray-800">Third Link</a>
                            </li>
                            <li>
                                <a class="text-gray-600 hover:text-gray-800">Fourth Link</a>
                            </li>
                        </nav>
                    </div>
                    <div class="lg:w-1/4 md:w-1/2 w-full px-4">
                        <h2 class="title-font font-medium text-light tracking-widest text-sm mb-3">SUBSCRIBE</h2>
                        <div class="flex xl:flex-nowrap md:flex-nowrap lg:flex-wrap flex-wrap justify-center items-end md:justify-start">
                            <div class="relative w-40 sm:w-auto xl:mr-4 lg:mr-0 sm:mr-4 mr-2">
                                <label for="footer-field" class="leading-7 text-sm text-gray-600">Placeholder</label>
                                <input type="text" id="footer-field" name="footer-field" class="w-full bg-gray-100 focus:bg-light bg-opacity-50 rounded border border-gray-300 focus:bg-transparent focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                            <button class="lg:mt-2 xl:mt-0 flex-shrink-0 inline-flex text-light bg-primary border-0 py-2 px-6 focus:outline-none hover:bg-selected-button rounded">Button</button>
                        </div>
                        <p class="text-gray-500 text-sm mt-2 md:text-left text-center">Bitters chicharrones fanny pack
                            <br class="lg:block hidden">waistcoat green juice
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-dark">
                <div class="container px-5 py-6 mx-auto flex items-center sm:flex-row flex-col">
                    <Link :href="route('home')">
                    <div class="shrink-0 flex items-center">
                        <ApplicationLogo class="block h-9 w-auto fill-current text-light" />
                        <span class="font-playfair-display ml-3 text-xl text-light">COACHTECH FREMA</span>
                    </div>
                    </Link>
                    <p class="text-sm text-gray-500 sm:ml-6 sm:mt-0 mt-4">© 2020 COACHTECH FREMA —
                        <a href="https://twitter.com/YAHIRO01101/" rel="noopener noreferrer" class="text-gray-600 ml-1" target="_blank">@YAHIRO</a>
                    </p>
                    <span class="inline-flex sm:ml-auto sm:mt-0 mt-4 justify-center sm:justify-start">
                        <a class="text-gray-500">
                            <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                            </svg>
                        </a>
                        <a class="ml-3 text-gray-500">
                            <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
                            </svg>
                        </a>
                        <a class="ml-3 text-gray-500">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"></path>
                            </svg>
                        </a>
                        <a class="ml-3 text-gray-500">
                            <svg fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="0" class="w-5 h-5" viewBox="0 0 24 24">
                                <path stroke="none" d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"></path>
                                <circle cx="4" cy="4" r="2" stroke="none"></circle>
                            </svg>
                        </a>
                    </span>
                </div>
            </div>
        </footer>
    </div>
</template>
