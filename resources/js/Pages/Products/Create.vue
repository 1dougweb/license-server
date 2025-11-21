<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import Card from '@/Components/UI/Card.vue';
import Input from '@/Components/UI/Input.vue';
import Button from '@/Components/UI/Button.vue';

const form = useForm({
    name: '',
    description: '',
    version: '',
    slug: '',
});

const submit = () => {
    form.post(route('products.store'));
};
</script>

<template>
    <Head title="Criar Produto" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Criar Produto
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card>
                    <form @submit.prevent="submit" class="space-y-6">
                        <Input
                            v-model="form.name"
                            label="Nome"
                            required
                            :error="form.errors.name"
                        />
                        <Input
                            v-model="form.version"
                            label="Versão"
                            required
                            :error="form.errors.version"
                        />
                        <Input
                            v-model="form.slug"
                            label="Slug"
                            :error="form.errors.slug"
                        />
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Descrição
                            </label>
                            <textarea
                                v-model="form.description"
                                rows="4"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            ></textarea>
                            <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                                {{ form.errors.description }}
                            </p>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <Link :href="route('products.index')">
                                <Button type="button" variant="secondary">Cancelar</Button>
                            </Link>
                            <Button type="submit" :disabled="form.processing">
                                Criar
                            </Button>
                        </div>
                    </form>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

