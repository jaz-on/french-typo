# TODO - Before WordPress.org Submission

## Deployment Files (to add after plugin approval)

Once the plugin is approved on WordPress.org, add these files:

- [ ] `.github/workflows/deploy-to-wordpress-org.yml` - GitHub Actions workflow for automated deployment
- [ ] `DEPLOYMENT.md` - Deployment documentation

These files will use the [10up WordPress Plugin Deploy Action](https://github.com/10up/action-wordpress-plugin-deploy).

## Setup Required

1. Add GitHub Secrets:
   - `SVN_USERNAME` - Your WordPress.org username
   - `SVN_PASSWORD` - Your WordPress.org password or app-specific password

2. The `.distignore` file is already in place and will be used by the deployment action.

