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
    <div>
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
        </div>
    </div>
</template>
