<script setup>
import { ref } from 'vue';

const props = defineProps({
    modelValue: String
});

const emit = defineEmits(['update:modelValue']);

const localPostal = ref(props.modelValue);

/**
 * 入力された内容を検証し、フォーマットをおこなう。
 * 正規表現を使用して非数字を除外し、最大7桁に制限する。
 */
const validatePostal = (postal) => {
    return postal.replace(/[^0-9]/g, '').slice(0, 7);
};

/**
 * 入力された郵便番号を検証し、フォーマットを適用する。
 */
const handleInput = () => {
    // 入力された郵便番号を検証
    const validatedPostal = validatePostal(localPostal.value);
    // ローカルの状態を更新
    localPostal.value = validatedPostal;
    // 検証済みの値を親コンポーネントに渡す
    emit('update:modelValue', validatedPostal);
};
</script>

<template>
    <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" v-model="localPostal" @input="handleInput" />
</template>
