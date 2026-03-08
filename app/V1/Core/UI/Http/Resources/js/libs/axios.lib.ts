import axios, { AxiosError, AxiosResponse } from "axios";
import pl from "../translations/pl";

const requestLimit = 10;
let requestCount = 0;
const resetInterval = 60000;

setInterval(() => {
    requestCount = 0;
}, resetInterval);

const axiosInstance = axios.create({
    baseURL: "",
    headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Content-Type": "application/json",
        Accept: "application/json",
    },
});

axiosInstance.interceptors.request.use(
    function (config) {
        if (requestCount >= requestLimit) {
            return Promise.reject(new Error(pl.validation.rateLimitExceeded(requestLimit)));
        }

        requestCount++;
        return config;
    },
    function (error) {
        return Promise.reject(error);
    }
);

axiosInstance.interceptors.response.use(
    function (response: AxiosResponse) {
        return response;
    },
    function (error: AxiosError) {
        const { response } = error;

        if (response) {
            console.error(`Error: ${response.status} - ${response.data?.message}`);
        }

        return Promise.reject(error);
    }
);
