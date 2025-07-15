import axios from 'axios'

const instance = axios.create({
  baseURL: '/', // Ganti jika API kamu ada di subdomain (mis: 'https://api.domain.com')
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'Content-Type': 'application/json'
  },
  withCredentials: true // WAJIB untuk Sanctum agar cookie bisa dikirim
})

export default instance
