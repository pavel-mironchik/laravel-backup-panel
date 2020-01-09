export default [
    {
        path: '/',
        component: require('./components/App').default,
        children: [
            {
                path: '',
                component: require('./components/screens/Backups').default
            }
        ]
    },
];
