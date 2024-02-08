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
let userIcon = usePage().props.value.auth.userImage ? ref('/storage/images/users/' + usePage().props.value.auth.userImage) : ref('/storage/images/users/default.jpg');
</script>

<template>
    <div>
        <div class="min-h-screen bg-black">

            <!-- Page Heading -->
            <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
                <a class="flex title-font font-medium items-center text-gray-900 mb-4 md:mb-0">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <ApplicationLogo class="block h-9 w-auto fill-current text-light" />
                    </div>
                    <span class="ml-3 text-xl text-light">COACHTECHフリマ</span>
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
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        ログアウト
                                    </DropdownLink>
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
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        ログアウト
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                    <a class="hidden md:block mr-5 text-light text-sm">お気に入り</a>
                    <a class="hidden md:block mr-5 text-light text-sm">検索</a>
                </nav>

                <!-- 出品ボタン（タブレット以上で表示） -->
                <button class="hidden md:inline-flex items-center text-light bg-primary font-bold border-0 py-1 px-3 focus:outline-none hover:bg-selected-button rounded text-base mt-4 md:mt-0" @click="navigateToCreateItem">出品</button>

                <!-- 検索エリア レスポンシブ対応（スマホサイズで表示）  -->
                <div class="relative md:hidden">
                    <input type="text" class="peer py-3 pe-0 ps-8 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-light text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-light focus:ring-0 disabled:opacity-50 disabled:pointer-events-none placeholder-light" placeholder="何をお探しですか？">
                    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-2 peer-disabled:opacity-50 peer-disabled:pointer-events-none">
                        <svg class="flex-shrink-0 h-4 w-4 text-light" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                    </div>
                </div>
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
        </div>
    </div>
</template>
