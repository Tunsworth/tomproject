<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                   Create Quiz
                </h2>
                <Link className="px-6 py-2 text-white bg-blue-500 rounded-md focus:outline-none" :href="route('quizzes.index')">All Quizzes</Link>
            </div>
        </template>
        <QuizForm :form="form" :categories="categories"></QuizForm>
    </AuthenticatedLayout>
</template>

<script setup>
import { computed, ref, reactive } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Label from '@/Components/InputLabel.vue';
import Input from '@/Components/TextInput.vue';
import TextArea from '@/Components/Textarea.vue';
import DropDown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import Checkbox from '@/Components/Checkbox.vue';
import CategoryModal from '@/Components/CategoryModal.vue'
import QuizForm from '@/Components/QuizForm.vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3';


const addQuestion = ref(false);
const questionIndex = ref(0);
const questions = ref([]);
const pageTab = ref('information')
const showCatOptions = ref(false)

defineProps({
    categories: Array,
});

const form = useForm({
        title: '',
        description: '',
        category_id: '',
        questions: [{
            answers:[{
                answer: '',
                correct_answer: false
            }],
                question:'',
            }]
    });
</script>

<style lang="scss" scoped>


</style>