<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
    user: Object,
});

const form = useForm({
    image_path: props.user.user_image.image_path,
    name: props.user.name,
    email: props.user.email,
    file: null,
});

/**
 * プロフィール画像のプレビュー用のURLを提供するリアクティブなプロパティ。
 *
 *  @type {string} - プロフィール画像のファイルパス
 */
let userIcon = form.image_path ? ref('/storage/images/users/' + form.image_path) : ref('/storage/images/users/default.jpg');

/**
 * ファイル選択時に呼び出され、画像プレビューを更新する。
 *
 * @param {Event} event - ファイル入力イベント
 */
const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => userIcon.value = e.target.result;
        reader.readAsDataURL(file);
    }
};

const triggerFileInput = () => {
    const fileInput = document.getElementById('file');
    if (fileInput) {
        fileInput.click();
    }
};

const updateProfileInformation = () => {
    form.post(route('profile.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => {
            form.recentlySuccessful = true;
        },
    });
};

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">アカウント情報</h2>

            <p class="mt-1 text-sm text-gray-600">
                アカウント名およびメールアドレスを変更します。
            </p>
        </header>

        <form @submit.prevent="updateProfileInformation" class="mt-6 space-y-6">
            <div>
                <div class="flex flex-row items-center">
                    <img class="inline-block h-[8rem] w-[8rem] rounded-full object-cover" :src="userIcon" alt="Image Description">
                    <div class="flex flex-col items-center mt-7">
                        <button type="button" class="ml-5 py-3 px-4  gap-x-2 text-sm font-semibold rounded-lg border border-gray-500 text-gray-500 hover:border-gray-400 hover:text-gray-400 disabled:opacity-50 disabled:pointer-events-none " @click="triggerFileInput">
                            プロフィール画像の変更
                        </button>
                        <p class="ml-6 mt-2 text-sm text-red-500 inline-flex" id="file_input_help">PNG, JPG or JPEG (MAX. 5MB)</p>
                    </div>
                </div>
                <input type="file" id="file" name="file" ref="file" @change="handleFileChange" @input="form.file = $event.target.files[0]" class="hidden">
                <InputError class="p-1" :message="form.errors.file" />
            </div>

            <div>
                <InputLabel for="name" value="アカウント名" />

                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="メールアドレス" />

                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="username" />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="props.mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Your email address is unverified.
                    <Link :href="route('verification.send')" method="post" as="button" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Click here to re-send the verification email.
                    </Link>
                </p>

                <div v-show="props.status === 'verification-link-sent'" class="mt-2 font-medium text-sm text-green-600">
                    A new verification link has been sent to your email address.
                </div>
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
