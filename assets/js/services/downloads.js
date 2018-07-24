import {url} from '../utils/api.js';
import axios from 'axios';

/**
 * Downloads API/URL Service
 * @author Gemma Black <gblackuk@gmail.com>
 */
export default class DownloadsService {

    /**
     * Gets restricted downloads and returns them in a CSV format
     * @link https://gist.github.com/javilobo8/097c30a233786be52070986d8cdb1743
     * @credits https://gist.github.com/javilobo8 
     * @param string url 
     * @return Promise (more useful on failure for retrieving downloads)
     */
    getRestrictedDownload(url, name = 'stats') {
        return axios.get(url, {
            withCredentials: true,
            headers: {
                'X-WP-Nonce': wpApiSettings.nonce
            },
            responseType: 'blob'
        }).then((response) => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', `${name}.csv`);
            document.body.appendChild(link);
            link.click();
        });
    }

    /**
     * Returns the nominations CSV URL
     * @param int awardId 
     * @return string
     */
    nominationsCSVURL(awardId) {
        return url + '/award/' + awardId + '/nominations?format=csv';
    }

    /**
     * Gets the votes CSV URL
     * @param {*} awardId 
     * @return string
     */
    votesCSVURL(awardId) {
        return url + '/award/' + awardId + '/votes?format=csv';
    }
}