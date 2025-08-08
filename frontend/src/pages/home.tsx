import { useTelegram } from "../shared/libs/telegram";

export const Home = () => {
    const { user } = useTelegram();

    return (
        <div className="home-page">
            <h1>Добро пожаловать{user?.first_name ? `, ${user.first_name}` : ''}!</h1>

            <div className="content">
                <p>Это ваш персональный помощник в Telegram</p>

                <section className="features">
                    <h2>Возможности:</h2>
                    <ul>
                        <li>🔐 Безопасное подключение</li>
                        <li>⚡ Мгновенная работа</li>
                        <li>🤖 Полная интеграция с ботом</li>
                    </ul>
                </section>
            </div>
        </div>
    );
};