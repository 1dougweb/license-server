<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Card from '@/Components/UI/Card.vue';
import Table from '@/Components/UI/Table.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Button from '@/Components/UI/Button.vue';

const props = defineProps({
    logs: Object,
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    license_id: props.filters?.license_id || '',
    domain: props.filters?.domain || '',
    device_id: props.filters?.device_id || '',
    is_valid: props.filters?.is_valid ?? '',
    date_from: props.filters?.date_from || '',
    date_to: props.filters?.date_to || '',
});

const submit = () => {
    form.get(route('logs.index'), {
        preserveState: true,
        preserveScroll: true,
    });
};

const clearFilters = () => {
    form.reset();
    router.get(route('logs.index'));
};
</script>

<template>
    <Head title="Logs de Validação" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Logs de Validação
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card>
                    <form @submit.prevent="submit" class="mb-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <Input
                                v-model="form.domain"
                                label="Domínio"
                                :error="form.errors.domain"
                            />
                            <Input
                                v-model="form.device_id"
                                label="Device ID"
                                :error="form.errors.device_id"
                            />
                            <Select
                                v-model="form.is_valid"
                                label="Status"
                                :options="[
                                    { value: '', label: 'Todos' },
                                    { value: '1', label: 'Válido' },
                                    { value: '0', label: 'Inválido' },
                                ]"
                            />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <Input
                                v-model="form.date_from"
                                label="Data Inicial"
                                type="date"
                            />
                            <Input
                                v-model="form.date_to"
                                label="Data Final"
                                type="date"
                            />
                        </div>
                        <div class="flex space-x-3">
                            <Button type="submit" :disabled="form.processing">
                                Filtrar
                            </Button>
                            <Button type="button" variant="secondary" @click="clearFilters">
                                Limpar
                            </Button>
                        </div>
                    </form>

                    <Table :headers="['Data', 'Licença', 'Domínio', 'Dispositivo', 'IP', 'Status', 'Mensagem']">
                        <tr v-for="log in logs.data" :key="log.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ new Date(log.created_at).toLocaleString() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ log.license?.product?.name || 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ log.domain || '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ log.device_id || '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ log.ip || '-' }}
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

                    <div v-if="logs.links" class="mt-4 flex justify-center">
                        <div v-for="link in logs.links" :key="link.label">
                            <a
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

