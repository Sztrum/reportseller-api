const pl = {
    validation: {
        rateLimitExceeded: (requestLimit: number) =>
            `Osiągnięto limit ${requestLimit} żądań na minutę. Spróbuj ponownie za chwilę.`,
    },
};

export default pl;
