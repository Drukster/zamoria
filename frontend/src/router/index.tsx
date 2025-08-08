import {createBrowserRouter} from "react-router-dom";
import {App} from "../layouts/app.tsx";
import { Home } from "../pages/home.tsx";

export const router = createBrowserRouter([
    {
        path: "",
        element: <App />,
        children: [
            {
                path: "/",
                element: <Home />
            }
        ]
    }
]);