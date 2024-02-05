<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
    userDetail: Object,
});

const form = useForm({
    postal: props.userDetail.postal,
    address: props.userDetail.address,
    introduction: props.userDetail.introduction,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">プロフィール情報</h2>

            <p class="mt-1 text-sm text-gray-600">
                住所および出品時の自己紹介文を変更します。
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">
            <div>
                <InputLabel for="postal" value="郵便番号" />

                <TextInput id="postal" type="text" class="mt-1 block w-full" v-model="form.postal" />

                <InputError class="mt-2" :message="form.errors.postal" />
            </div>

            <div>
                <InputLabel for="address" value="住所" />

                <TextInput id="address" type="text" class="mt-1 block w-full" v-model="form.address" />

                <InputError class="mt-2" :message="form.errors.address" />
            </div>

            <div>
                <InputLabel for="building" value="建物" />

                <TextInput id="building" type="text" class="mt-1 block w-full" v-model="form.building" />

                <InputError class="mt-2" :message="form.errors.building" />
            </div>

            <div>
                <InputLabel for="introduction" value="自己紹介" />

                <textarea class="py-3 px-4 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3" placeholder="出品ページの自己紹介文を入力してください。" v-model="form.introduction"></textarea>

                <InputError class="mt-2" :message="form.errors.introduction" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">更新</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">更新しました。</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
