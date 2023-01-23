<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                   Create Quiz
                </h2>
                <Link className="px-6 py-2 text-white bg-blue-500 rounded-md focus:outline-none" :href="route('quizzes.index')">Back</Link>
            </div>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex">
                        <a @click.prevent="quizInfo"><div class="tab" >
                            Information
                        </div></a>
                        <a @click.prevent="quizQuestions">
                            <div class="tab" >
                            Questions
                            </div>
                        </a>
                    </div>
                        
                    <div class="p-6 bg-white border-b border-gray-200">
                        
                        <div className="flex items-center  mb-6">
                       
                        </div>
                        <form name="createForm" @submit.prevent="submit">
                                <div v-if="page == 0" className="flex flex-col">
                                    <div className="mb-4">
                                        <Label for="title" value="Title" />
                                        <Input 
                                            id="title" 
                                            type="text" 
                                            class="mt-1 block w-full" 
                                            v-model="form.title" 
                                            autofocus />
                                        <span className="text-red-600" v-if="form.errors.title">
                                            {{ form.errors.title }}
                                        </span>
                                    </div>
                                    <div className="mb-4">
                                        <Label for="description" value="Description" />
                                        
                                        <TextArea 
                                            id="body" 
                                            class="mt-1 block w-full" 
                                            v-model="form.description" 
                                            autofocus />
                                        <span className="text-red-600" v-if="form.errors.description">
                                            {{ form.errors.description }}
                                        </span>
                                    </div>
                                    <div className="mb-4">
                                        <Label for="category" value="Category" />
                                        <select v-model="form.category_id" class="form-control mt-1 block w-full">
                                            <option v-for="option in categories" :value="option.id">
                                                {{ option.title}}
                                            </option>
                                        </select>
                                        <span className="text-red-600" v-if="form.errors.category">
                                            {{ form.errors.category_id }}
                                        </span>
                                        <span className="text-red-600" v-if="form.errors.title">
                                            {{ form.errors.title }}
                                        </span>
                                    </div>
                                    <div class="flex justify-end">
                                        <button className="px-6 py-2 text-white bg-green-500 rounded-md focus:outline-none" v-if="questions.length == 0" @click.prevent="startQuestion(index)"> start question </button>
                                    </div>
                                </div>
                                <div v-else-if="page != 0">
                                    <div v-for="(question , index) in form.questions" :key="index">
                                        <!-- <div v-show="index == page - 1"> -->
                                        <div >
                                            <div className="mb-4">
                                                <Label for="question" :value="`Question ${index+1}`" />
                                                <TextArea  
                                                    id="question" 
                                                    type="text" 
                                                    class="mt-1 block w-full" 
                                                    v-model="question.question" 
                                                    autofocus />
                                                <span className="text-red-600" v-if="form.errors.title">
                                                    {{ form.errors.title }}
                                                </span>
                                                <div v-for="(answer, number) in question.answers"  :key="number" className="mb-4">
                                                    <Label for="answer" :value="`Answer ${number + 1}`" />
                                                    <TextArea 
                                                        id="body" 
                                                        class="mt-1 block w-full" 
                                                        v-model="answer.answer" 
                                                        autofocus />
                                                    <span className="text-red-600" v-if="form.errors.description">
                                                        {{ form.errors.description }}
                                                    </span>
                                                    <!-- <button @click.prevent="newAnswer(number)"> add answer </button> -->
                                                <!-- </div> -->
                                                </div>
                                            </div>
                                            <div class="flex justify-between">
                                                <button v-if="questions[index].answers.length <= 4" className="px-6 py-2 text-white bg-blue-500 rounded-md focus:outline-none" @click.prevent="newAnswer(index)"> add answer </button>
                                                <button className="px-6 py-2 text-white bg-green-500 rounded-md focus:outline-none" :id="number" v-if="questions[index].answers.length >= 2 && questions.length == index + 1 " @click.prevent="newQuestion(number)"> add question </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             
  
                                <div className="mt-4">
                                    <button
                                        type="submit"
                                        className="px-6 py-2 font-bold text-white bg-green-500 rounded"
                                    >
                                        Save
                                    </button>
                                </div>

                                <!-- <div class="page_wrapper">
                                <div
                                v-for="(pageNumber, i) in totalPage"
                                :key="i"
                                @click="changePage(pageNumber)"
                                class="page"
                               
                                >
                                {{ pageNumber }}{{ i }}
                                </div>
                            </div> -->
                            </form>
                    </div>
                </div>
            </div>
        </div>
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
import { Head, Link, useForm } from '@inertiajs/vue3';


const addQuestion = ref(false);
const questionIndex = ref(0);
const questions = ref([]);
const page = ref(0)

defineProps({
    categories: Array,
});

const form = useForm({
        title: '',
        description: '',
        category_id: '',
        questions: [{
            question: '',
            answers: [
                {
                answer: '' 
                }
            ]
        }]
});

const submit = () => {
    form.post(route('quizzes.store'));
};

const createQuiz = () => {
    form.post(route('quizzes.store'));
    // addQuestion = true
};

const  startQuestion = () => {
    page.value =  ++questionIndex.value 
    addQuestion.value = true
    this.form.questions.push({
        question:'',
        answers:[]
    })
};
const  newQuestion = (index) => {
    // page.value = ++questionIndex.value 
    addQuestion.value = true
    this.form.questions.push({
        question:'',
        answers:[]
    })
    
};
const  newAnswer = (index) => {
    questions.value[index].answers.push({
        answer:''
    })
};

const  quizInfo = () => {
    page.value = 0
};

const  quizQuestions = () => {
    page.value = ++questionIndex.value 
    addQuestion.value = true
    // initialise first question if one hasntallready been created
    if(questions.value.length  < 1){
        questions.value.push({
        question:'',
        answers:[]
    }) 
    }
};

</script>

<style lang="scss" scoped>

.tab {
    // border: 1px solid red;
    box-shadow: grey 0.1rem 0.1rem 0.1rem 0.1rem;
    height: 2rem;
    padding:0 1rem;
 }
</style>