<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Card from '@/Components/UI/Card.vue';
import Table from '@/Components/UI/Table.vue';
import Button from '@/Components/UI/Button.vue';

defineProps({
    products: Object,
});
</script>

<template>
    <Head title="Produtos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Produtos
                </h2>
                <Link :href="route('products.create')">
                    <Button>Novo Produto</Button>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card>
                    <Table :headers="['Nome', 'Versão', 'Slug', 'Descrição', 'Ações']">
                        <tr v-for="product in products.data" :key="product.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ product.name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ product.version }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ product.slug }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ product.description || '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <Link :href="route('products.edit', product.id)" class="text-blue-600 hover:text-blue-900 mr-3">
                                    Editar
                                </Link>
                                <button
                                    @click="router.delete(route('products.destroy', product.id))"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Excluir
                                </button>
                            </td>
                        </tr>
                    </Table>

                    <div v-if="products.links" class="mt-4 flex justify-center">
                        <div v-for="link in products.links" :key="link.label">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                :class="[
                                    'px-3 py-2 mx-1 rounded-md',
                                    link.active ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300',
                                ]"
                                v-html="link.label"
                            />
                            <span
                                v-else
                                class="px-3 py-2 mx-1 rounded-md bg-gray-100 text-gray-400"
                                v-html="link.label"
                            />
                        </div>
                    </div>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

