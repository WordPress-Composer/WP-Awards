import AwardService from '../services/awards';
import WinnerService from '../services/winners';
import CategoryService from '../services/categories';
import FinalistService from '../services/finalists';
import NominationsService from '../services/nominations.js';
import StatsService from '../services/stats.js';
import DownloadsService from '../services/downloads.js';

export default {
    install(Vue, options)
    {
        Vue.prototype.$api = {
            awards:         new AwardService,
            winners:        new WinnerService,
            categories:     new CategoryService,
            finalists:      new FinalistService,
            nominations:    new NominationsService,
            statistics:     new StatsService,
            downloads:      new DownloadsService
        }
    }
}
