<script setup>
import { computed, onMounted, onUnmounted, watch } from 'vue';
import Label from '@/Components/InputLabel.vue';
import Input from '@/Components/TextInput.vue';
import TextArea from '@/Components/Textarea.vue';
import DropDown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import Checkbox from '@/Components/Checkbox.vue';
import CategoryModal from '@/Components/CategoryModal.vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['close']);

watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = null;
        }
    }
);

const categoryForm = useForm({
    title: '',
    description: '',
    
});

const submitNewCategory = () => {
    categoryForm.post(route('categories.store'));
};

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = null;
});

const maxWidthClass = computed(() => {
    return {
        sm: 'sm:max-w-sm',
        md: 'sm:max-w-md',
        lg: 'sm:max-w-lg',
        xl: 'sm:max-w-xl',
        '2xl': 'sm:max-w-2xl',
    }[props.maxWidth];
});
</script>

<template>
    <teleport to="body">
        <transition leave-active-class="duration-200">
            <div v-show="show" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" scroll-region>
                <transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div v-show="show" class="fixed inset-0 transform transition-all" @click="close">
                        <div class="absolute inset-0 bg-gray-500 opacity-75" />
                    </div>
                </transition>

                <transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <div
                        v-show="show"
                        class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto"
                        :class="maxWidthClass"
                    >
                        <div v-if="show">
                            <div class="p-5">
                                <form name="createForm" @submit.prevent="submitNewCategory">
                                    <div className="mb-4">
                                        <Label for="category_title" value="Category Title" />
                                        <Input 
                                            id="title" 
                                            type="text" 
                                            class="mt-1 block w-full" 
                                            v-model="categoryForm.title" 
                                            autofocus />
                                        <span className="text-red-600" v-if="categoryForm.errors.title">
                                            {{ categoryForm.errors.title }}
                                        </span>
                                    </div>
                                    <div className="mb-4">
                                        <Label for="category_description" value="Category Description" />
                                        <TextArea 
                                            id="body" 
                                            class="mt-1 block w-full" 
                                            v-model="categoryForm.description" 
                                            autofocus />
                                        <span className="text-red-600" v-if="categoryForm.errors.description">
                                            {{ categoryForm.errors.description }}
                                        </span>
                                    </div>  
                                    <div class="flex justify-between">
                                        <button @submit.prevent="submitNewCategory"
                                        type="submit"
                                        className="px-6 py-2 font-bold text-white bg-green-500 rounded"
                                        >
                                            Save
                                        </button>   
                                        <button  @click="close" className="px-6 py-2 font-bold text-white bg-gray-500 rounded"> Cancel </button>   
                                    </div>
                                </form>
                             </div>
                        </div>
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
</template>
