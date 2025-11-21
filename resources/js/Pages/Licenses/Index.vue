<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Card from '@/Components/UI/Card.vue';
import Table from '@/Components/UI/Table.vue';
import Button from '@/Components/UI/Button.vue';

defineProps({
    licenses: Object,
});
</script>

<template>
    <Head title="Licenças" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Licenças
                </h2>
                <Link :href="route('licenses.create')">
                    <Button>Nova Licença</Button>
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card>
                    <Table :headers="['Produto', 'Token', 'Domínio', 'Limite', 'Expira', 'Status', 'Ações']">
                        <tr v-for="license in licenses.data" :key="license.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ license.product?.name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-mono text-xs">
                                {{ license.token.substring(0, 20) }}...
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ license.allowed_domain || '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ license.device_limit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ license.expires_at ? new Date(license.expires_at).toLocaleDateString() : 'Sem expiração' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="[
                                        'px-2 py-1 text-xs rounded-full',
                                        license.is_blocked ? 'bg-red-100 text-red-800' : 
                                        (license.expires_at && new Date(license.expires_at) < new Date()) ? 'bg-yellow-100 text-yellow-800' :
                                        'bg-green-100 text-green-800',
                                    ]"
                                >
                                    {{ license.is_blocked ? 'Bloqueada' : 
                                       (license.expires_at && new Date(license.expires_at) < new Date()) ? 'Expirada' : 'Ativa' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <Link :href="route('licenses.edit', license.id)" class="text-blue-600 hover:text-blue-900">
                                    Editar
                                </Link>
                                <button
                                    @click="router.post(route('licenses.toggle-block', license.id))"
                                    :class="license.is_blocked ? 'text-green-600 hover:text-green-900' : 'text-yellow-600 hover:text-yellow-900'"
                                >
                                    {{ license.is_blocked ? 'Desbloquear' : 'Bloquear' }}
                                </button>
                                <button
                                    @click="router.post(route('licenses.regenerate-token', license.id))"
                                    class="text-purple-600 hover:text-purple-900"
                                >
                                    Regenerar Token
                                </button>
                                <button
                                    @click="router.delete(route('licenses.destroy', license.id))"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Excluir
                                </button>
                            </td>
                        </tr>
                    </Table>

                    <div v-if="licenses.links" class="mt-4 flex justify-center">
                        <div v-for="link in licenses.links" :key="link.label">
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

