import axios from 'axios'

const APIUrl = 'api/guestbook'

export default {
    async getAllMessages() {
        return await axios.get(APIUrl)
            .then( (response) => {
                return JSON.parse(response.data)
            })
            .catch( (error) => {
                console.log('guestbook api service/getAllMessages', error)
            })
            .finally( () => {

            })
    },

    async deleteMessage(id) {
        const payload = {
            data: {
                id: id,
            },
        }
        return axios.delete(APIUrl, payload)
            .then( (response) => {
                return response.data
            })
            .catch( (error) => {
                console.log('guestbook api service/delete', error)
            })
            .finally( () => {

            })
    },

    async replyMessage(id, author, text) {
        const payload = {
            repliedTo: id,
            author: author,
            text: text,
        }
        return axios.post(APIUrl, payload)
            .then( (response) => {
                return response.data
            })
            .catch( (error) => {
                console.log('guestbook api service/reply', error)
            })
            .finally( () => {

            })
    },

    async updateMessage(id, text) {
        const payload = {
            id: id,
            text: text,
        }
        return axios.patch(APIUrl, payload)
            .then( (response) => {
                return response.data
            })
            .catch( (error) => {
                console.log('guestbook api service/update', error)
            })
            .finally( () => {

            })
    }
}