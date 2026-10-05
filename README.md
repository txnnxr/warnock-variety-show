# Warnock Variety Show

## Deploying to DreamHost

The server can't run `npm run build` (it runs out of memory), so front-end
assets are built locally and uploaded:

    npm run upload

This builds, copies `public/build` to the server, and refreshes the view cache.
