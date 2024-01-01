// useUpdateStatus.js
import { ref } from 'vue';
import axios from 'axios';

export function useUpdateStatus() {
    const status = ref(null);

    const updateStatus = async () => {
        try {
            const response = await axios.post('/update-status');
            status.value = response.data.message;
        } catch (error) {
            console.error('Error updating status', error);
        }
    };

    return { status, updateStatus };
}
