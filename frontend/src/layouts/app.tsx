import {Outlet} from 'react-router-dom';
import {useTelegram} from '../shared/libs/telegram';
import {useEffect} from 'react';

export const App = () => {
    const {webApp} = useTelegram();

    useEffect(() => {
        if (!webApp) return;

        webApp.ready();
        webApp.setHeaderColor('#6A35FF');
        webApp.setBackgroundColor('#F5F5F7');
    }, [webApp]);

    return (
        <div className="tg-app">
            <Outlet/>
            {webApp && (
                <button
                    className="tg-main-button"
                    onClick={() => webApp.sendData('action')}
                >
                    Основное действие
                </button>
            )}
        </div>
    );
};