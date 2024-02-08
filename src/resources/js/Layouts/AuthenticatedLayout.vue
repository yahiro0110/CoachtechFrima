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
                </a>
                <nav class="md:ml-auto flex flex-wrap items-center text-base justify-center">
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <!-- Settings Dropdown -->
                        <div class="ml-3 relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <img class="inline-block h-[2rem] w-[2rem] rounded-full object-cover m-2" :src="userIcon" alt="Image Description">
                                    <span class="inline-flex rounded-md">
                                        <button type="button" class="inline-flex items-center mr-5 border border-transparent text-sm leading-4 font-medium rounded-md text-light focus:outline-none transition ease-in-out duration-150">
                                            {{ $page.props.auth.user.name }}
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        Logout
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                    <a class="mr-5 text-light text-sm">お気に入り</a>
                    <a class="mr-5 text-light text-sm">検索</a>
                </nav>
                <button class="inline-flex items-center text-light bg-primary font-bold border-0 py-1 px-3 focus:outline-none hover:bg-selected-button rounded text-base mt-4 md:mt-0" @click="navigateToCreateItem">出品</button>
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
