<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Card from '@/Components/UI/Card.vue';
import Table from '@/Components/UI/Table.vue';

defineProps({
    stats: Object,
    recentLogs: Array,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <Card>
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Total de Produtos</p>
                            <p class="text-3xl font-bold text-gray-900">{{ stats.total_products }}</p>
                        </div>
                    </Card>
                    <Card>
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Total de Licenças</p>
                            <p class="text-3xl font-bold text-gray-900">{{ stats.total_licenses }}</p>
                        </div>
                    </Card>
                    <Card>
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Licenças Ativas</p>
                            <p class="text-3xl font-bold text-green-600">{{ stats.active_licenses }}</p>
                        </div>
                    </Card>
                    <Card>
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Licenças Expiradas</p>
                            <p class="text-3xl font-bold text-red-600">{{ stats.expired_licenses }}</p>
                        </div>
                    </Card>
                </div>

                <!-- Recent Logs -->
                <Card title="Últimos Logs de Validação">
                    <Table :headers="['Data', 'Licença', 'Domínio', 'Dispositivo', 'Status', 'Mensagem']">
                        <tr v-for="log in recentLogs" :key="log.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ new Date(log.created_at).toLocaleString() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ log.license?.product?.name || 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ log.domain || 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ log.device_id || 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="[
                                        'px-2 py-1 text-xs rounded-full',
                                        log.is_valid ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800',
                                    ]"
                                >
                                    {{ log.is_valid ? 'Válido' : 'Inválido' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ log.message }}
                            </td>
                        </tr>
                    </Table>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
