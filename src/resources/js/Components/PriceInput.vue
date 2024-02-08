<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    modelValue: String
});

const emit = defineEmits(['update:modelValue']);

const localPrice = ref(props.modelValue);

/**
 * 入力された内容を検証し、フォーマットをおこなう。
 * 正規表現を使用して非数字を除外する。
 */
const validatePrice = (price) => {
    return price.replace(/[^0-9]/g, '').slice(0, 7);
};

/**
 * 入力された価格を検証し、フォーマットを適用する。
 */
const handleInput = () => {
    // 入力された価格を検証
    const validatedPrice = validatePrice(localPrice.value);
    // ローカルの状態を更新
    localPrice.value = validatedPrice;
    // 検証済みの値を親コンポーネントに渡す
    emit('update:modelValue', validatedPrice);
};

watch(() => props.modelValue, (newVal) => {
    localPrice.value = newVal || ''; // modelValueが更新されたら、それに応じてlocalPriceを更新
}, { immediate: true });
</script>

<template>
    <div class="relative border-none m-0 p-0">
        <input type="text" id="hs-input-with-leading-and-trailing-icon" name="hs-input-with-leading-and-trailing-icon" class="py-3 block w-full border-gray-300 shadow-sm rounded-md text-base focus:z-10 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none text-right" placeholder="0" v-model="localPrice" @input="handleInput">
        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
            <span class="text-gray-500">¥</span>
        </div>
    </div>
</template>
