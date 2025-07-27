// vite.config.js
import { defineConfig } from "file:///C:/Users/Lion-PC/Downloads/alemedu01-master/alemedu01-master/node_modules/vite/dist/node/index.js";
import laravel from "file:///C:/Users/Lion-PC/Downloads/alemedu01-master/alemedu01-master/node_modules/laravel-vite-plugin/dist/index.js";
import html from "file:///C:/Users/Lion-PC/Downloads/alemedu01-master/alemedu01-master/node_modules/@rollup/plugin-html/dist/es/index.js";
import { glob } from "file:///C:/Users/Lion-PC/Downloads/alemedu01-master/alemedu01-master/node_modules/glob/dist/esm/index.js";
import path from "path";
import viteCompression from "file:///C:/Users/Lion-PC/Downloads/alemedu01-master/alemedu01-master/node_modules/vite-plugin-compression/dist/index.mjs";
var __vite_injected_original_dirname = "C:\\Users\\Lion-PC\\Downloads\\alemedu01-master\\alemedu01-master";
function GetFilesArray(query) {
  return Array.from(new Set(glob.sync(query)));
}
var pageJsFiles = GetFilesArray("resources/assets/js/*.js");
var vendorJsFiles = GetFilesArray("resources/assets/vendor/js/*.js").filter((file) => !file.includes("calendar.js") && !file.includes("modern-calendar.js"));
var LibsJsFiles = GetFilesArray("resources/assets/vendor/libs/**/*.js");
var CoreScssFiles = GetFilesArray("resources/assets/vendor/scss/**/!(_)*.scss");
var RtlScssFiles = GetFilesArray("resources/assets/vendor/scss/rtl/**/!(_)*.scss");
var LibsScssFiles = GetFilesArray("resources/assets/vendor/libs/**/!(_)*.scss");
var LibsCssFiles = GetFilesArray("resources/assets/vendor/libs/**/*.css");
var FontsScssFiles = GetFilesArray("resources/assets/vendor/fonts/**/!(_)*.scss");
var CookieConsentCss = GetFilesArray("resources/css/cookie-consent.css");
function libsWindowAssignment() {
  return {
    name: "libsWindowAssignment",
    transform(src, id) {
      if (id.includes("jkanban.js")) {
        return src.replace("this.jKanban", "window.jKanban");
      } else if (id.includes("vfs_fonts")) {
        return src.replaceAll("this.pdfMake", "window.pdfMake");
      }
    }
  };
}
var vite_config_default = defineConfig({
  plugins: [
    laravel({
      input: [
        "resources/css/app.css",
        "resources/assets/css/alemedu.css",
        "resources/js/app.js",
        "resources/js/sitemap-manager.js",
        "resources/assets/vendor/js/monitoring/active-visitors.js",
        "resources/js/articles.js",
        "resources/js/school-classes.js",
        "resources/css/cookie-consent.css",
        "resources/js/subjects.js",
        "resources/js/articles-form.js",
        "resources/assets/css/pages/notifications.css",
        "resources/assets/css/articles/articles-list-style.css",
        "resources/assets/js/articles/articles-management.js",
        "resources/assets/css/articles/article-details.css",
        "resources/assets/js/articles/article-details.js",
        "resources/assets/js/pages/monitoring.js",
        "resources/assets/css/pages/security.css",
        "resources/assets/js/pages/security/trusted-ips.js",
        "resources/assets/js/pages/security.js",
        "resources/assets/js/pages/security/blocked-ips.js",
        "resources/css/footer-front.css",
        "resources/assets/css/pages/articles.css",
        "resources/assets/css/dashboard.css",
        "resources/js/articles-show.js",
        "resources/assets/js/pages/dashboard.js",
        "resources/assets/css/pages/school-classes.css",
        "resources/assets/js/pages/performance.js",
        "resources/assets/css/pages/performance.css",
        "resources/assets/js/pages/performance-metrics.js",
        "resources/assets/js/pages/news.js",
        "resources/css/pages/dashboard.css",
        "resources/assets/css/pages/about-us.css",
        "resources/assets/js/pages/userlist.js",
        "resources/scss/base/pages/news.scss",
        ...pageJsFiles,
        ...vendorJsFiles,
        ...LibsJsFiles,
        "resources/js/laravel-user-management.js",
        ...CoreScssFiles,
        ...RtlScssFiles,
        ...LibsScssFiles,
        ...LibsCssFiles,
        ...FontsScssFiles
      ],
      refresh: true
    }),
    libsWindowAssignment(),
    viteCompression()
  ],
  resolve: {
    alias: {
      "~": path.resolve(__vite_injected_original_dirname, "node_modules"),
      "@": path.resolve(__vite_injected_original_dirname, "resources")
    }
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJDOlxcXFxVc2Vyc1xcXFxMaW9uLVBDXFxcXERvd25sb2Fkc1xcXFxhbGVtZWR1MDEtbWFzdGVyXFxcXGFsZW1lZHUwMS1tYXN0ZXJcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZmlsZW5hbWUgPSBcIkM6XFxcXFVzZXJzXFxcXExpb24tUENcXFxcRG93bmxvYWRzXFxcXGFsZW1lZHUwMS1tYXN0ZXJcXFxcYWxlbWVkdTAxLW1hc3RlclxcXFx2aXRlLmNvbmZpZy5qc1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9pbXBvcnRfbWV0YV91cmwgPSBcImZpbGU6Ly8vQzovVXNlcnMvTGlvbi1QQy9Eb3dubG9hZHMvYWxlbWVkdTAxLW1hc3Rlci9hbGVtZWR1MDEtbWFzdGVyL3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHsgZGVmaW5lQ29uZmlnIH0gZnJvbSAndml0ZSc7XG5pbXBvcnQgbGFyYXZlbCBmcm9tICdsYXJhdmVsLXZpdGUtcGx1Z2luJztcbmltcG9ydCBodG1sIGZyb20gJ0Byb2xsdXAvcGx1Z2luLWh0bWwnO1xuaW1wb3J0IHsgZ2xvYiB9IGZyb20gJ2dsb2InO1xuaW1wb3J0IHBhdGggZnJvbSAncGF0aCc7XG5pbXBvcnQgdml0ZUNvbXByZXNzaW9uIGZyb20gJ3ZpdGUtcGx1Z2luLWNvbXByZXNzaW9uJztcblxuLyoqXG4gKiBHZXQgRmlsZXMgZnJvbSBhIGRpcmVjdG9yeVxuICogQHBhcmFtIHtzdHJpbmd9IHF1ZXJ5XG4gKiBAcmV0dXJucyBhcnJheVxuICovXG5mdW5jdGlvbiBHZXRGaWxlc0FycmF5KHF1ZXJ5KSB7XG4gIHJldHVybiBBcnJheS5mcm9tKG5ldyBTZXQoZ2xvYi5zeW5jKHF1ZXJ5KSkpOyAvLyBSZW1vdmUgZHVwbGljYXRlIGZpbGVzIGJ5IHVzaW5nIFNldCBpbml0aWFsbHlcbn1cbi8qKlxuICogSnMgRmlsZXNcbiAqL1xuLy8gUGFnZSBKUyBGaWxlc1xuY29uc3QgcGFnZUpzRmlsZXMgPSBHZXRGaWxlc0FycmF5KCdyZXNvdXJjZXMvYXNzZXRzL2pzLyouanMnKTtcblxuLy8gUHJvY2Vzc2luZyBWZW5kb3IgSlMgRmlsZXMgKGV4Y2x1ZGluZyBjYWxlbmRhci5qcylcbmNvbnN0IHZlbmRvckpzRmlsZXMgPSBHZXRGaWxlc0FycmF5KCdyZXNvdXJjZXMvYXNzZXRzL3ZlbmRvci9qcy8qLmpzJykuZmlsdGVyKGZpbGUgPT4gIWZpbGUuaW5jbHVkZXMoJ2NhbGVuZGFyLmpzJykgJiYgIWZpbGUuaW5jbHVkZXMoJ21vZGVybi1jYWxlbmRhci5qcycpKTtcblxuLy8gUHJvY2Vzc2luZyBMaWJzIEpTIEZpbGVzXG5jb25zdCBMaWJzSnNGaWxlcyA9IEdldEZpbGVzQXJyYXkoJ3Jlc291cmNlcy9hc3NldHMvdmVuZG9yL2xpYnMvKiovKi5qcycpO1xuXG4vKipcbiAqIFNjc3MgRmlsZXNcbiAqL1xuLy8gUHJvY2Vzc2luZyBDb3JlLCBUaGVtZXMgJiBQYWdlcyBTY3NzIEZpbGVzXG5jb25zdCBDb3JlU2Nzc0ZpbGVzID0gR2V0RmlsZXNBcnJheSgncmVzb3VyY2VzL2Fzc2V0cy92ZW5kb3Ivc2Nzcy8qKi8hKF8pKi5zY3NzJyk7XG5jb25zdCBSdGxTY3NzRmlsZXMgPSBHZXRGaWxlc0FycmF5KCdyZXNvdXJjZXMvYXNzZXRzL3ZlbmRvci9zY3NzL3J0bC8qKi8hKF8pKi5zY3NzJyk7XG5cbi8vIFByb2Nlc3NpbmcgTGlicyBTY3NzICYgQ3NzIEZpbGVzXG5jb25zdCBMaWJzU2Nzc0ZpbGVzID0gR2V0RmlsZXNBcnJheSgncmVzb3VyY2VzL2Fzc2V0cy92ZW5kb3IvbGlicy8qKi8hKF8pKi5zY3NzJyk7XG5jb25zdCBMaWJzQ3NzRmlsZXMgPSBHZXRGaWxlc0FycmF5KCdyZXNvdXJjZXMvYXNzZXRzL3ZlbmRvci9saWJzLyoqLyouY3NzJyk7XG5cbi8vIFByb2Nlc3NpbmcgRm9udHMgU2NzcyBGaWxlc1xuY29uc3QgRm9udHNTY3NzRmlsZXMgPSBHZXRGaWxlc0FycmF5KCdyZXNvdXJjZXMvYXNzZXRzL3ZlbmRvci9mb250cy8qKi8hKF8pKi5zY3NzJyk7XG5cbi8vIEFkZCBjb29raWUgY29uc2VudCBDU1NcbmNvbnN0IENvb2tpZUNvbnNlbnRDc3MgPSBHZXRGaWxlc0FycmF5KCdyZXNvdXJjZXMvY3NzL2Nvb2tpZS1jb25zZW50LmNzcycpO1xuXG4vLyBQcm9jZXNzaW5nIFdpbmRvdyBBc3NpZ25tZW50IGZvciBMaWJzIGxpa2UgakthbmJhbiwgcGRmTWFrZVxuZnVuY3Rpb24gbGlic1dpbmRvd0Fzc2lnbm1lbnQoKSB7XG4gIHJldHVybiB7XG4gICAgbmFtZTogJ2xpYnNXaW5kb3dBc3NpZ25tZW50JyxcblxuICAgIHRyYW5zZm9ybShzcmMsIGlkKSB7XG4gICAgICBpZiAoaWQuaW5jbHVkZXMoJ2prYW5iYW4uanMnKSkge1xuICAgICAgICByZXR1cm4gc3JjLnJlcGxhY2UoJ3RoaXMuakthbmJhbicsICd3aW5kb3cuakthbmJhbicpO1xuICAgICAgfSBlbHNlIGlmIChpZC5pbmNsdWRlcygndmZzX2ZvbnRzJykpIHtcbiAgICAgICAgcmV0dXJuIHNyYy5yZXBsYWNlQWxsKCd0aGlzLnBkZk1ha2UnLCAnd2luZG93LnBkZk1ha2UnKTtcbiAgICAgIH1cbiAgICB9XG4gIH07XG59XG5cbmV4cG9ydCBkZWZhdWx0IGRlZmluZUNvbmZpZyh7XG4gIHBsdWdpbnM6IFtcbiAgICBsYXJhdmVsKHtcbiAgICAgIGlucHV0OiBbXG4gICAgICAgICdyZXNvdXJjZXMvY3NzL2FwcC5jc3MnLFxuICAgICAgICAncmVzb3VyY2VzL2Fzc2V0cy9jc3MvYWxlbWVkdS5jc3MnLFxuICAgICAgICAncmVzb3VyY2VzL2pzL2FwcC5qcycsXG4gICAgICAgICdyZXNvdXJjZXMvanMvc2l0ZW1hcC1tYW5hZ2VyLmpzJyxcbiAgICAgICAgJ3Jlc291cmNlcy9hc3NldHMvdmVuZG9yL2pzL21vbml0b3JpbmcvYWN0aXZlLXZpc2l0b3JzLmpzJyxcbiAgICAgICAgJ3Jlc291cmNlcy9qcy9hcnRpY2xlcy5qcycsXG4gICAgICAgICdyZXNvdXJjZXMvanMvc2Nob29sLWNsYXNzZXMuanMnLFxuICAgICAgICAncmVzb3VyY2VzL2Nzcy9jb29raWUtY29uc2VudC5jc3MnLFxuICAgICAgICAncmVzb3VyY2VzL2pzL3N1YmplY3RzLmpzJyxcbiAgICAgICAgJ3Jlc291cmNlcy9qcy9hcnRpY2xlcy1mb3JtLmpzJyxcbiAgICAgICAgJ3Jlc291cmNlcy9hc3NldHMvY3NzL3BhZ2VzL25vdGlmaWNhdGlvbnMuY3NzJyxcbiAgICAgICAgJ3Jlc291cmNlcy9hc3NldHMvY3NzL2FydGljbGVzL2FydGljbGVzLWxpc3Qtc3R5bGUuY3NzJyxcbiAgICAgICAgJ3Jlc291cmNlcy9hc3NldHMvanMvYXJ0aWNsZXMvYXJ0aWNsZXMtbWFuYWdlbWVudC5qcycsXG4gICAgICAgICdyZXNvdXJjZXMvYXNzZXRzL2Nzcy9hcnRpY2xlcy9hcnRpY2xlLWRldGFpbHMuY3NzJyxcbiAgICAgICAgJ3Jlc291cmNlcy9hc3NldHMvanMvYXJ0aWNsZXMvYXJ0aWNsZS1kZXRhaWxzLmpzJyxcbiAgICAgICAgJ3Jlc291cmNlcy9hc3NldHMvanMvcGFnZXMvbW9uaXRvcmluZy5qcycsXG4gICAgICAgICdyZXNvdXJjZXMvYXNzZXRzL2Nzcy9wYWdlcy9zZWN1cml0eS5jc3MnLFxuICAgICAgICAncmVzb3VyY2VzL2Fzc2V0cy9qcy9wYWdlcy9zZWN1cml0eS90cnVzdGVkLWlwcy5qcycsXG4gICAgICAgICdyZXNvdXJjZXMvYXNzZXRzL2pzL3BhZ2VzL3NlY3VyaXR5LmpzJyxcbiAgICAgICAgJ3Jlc291cmNlcy9hc3NldHMvanMvcGFnZXMvc2VjdXJpdHkvYmxvY2tlZC1pcHMuanMnLFxuICAgICAgICAncmVzb3VyY2VzL2Nzcy9mb290ZXItZnJvbnQuY3NzJyxcbiAgICAgICAgJ3Jlc291cmNlcy9hc3NldHMvY3NzL3BhZ2VzL2FydGljbGVzLmNzcycsXG4gICAgICAgICdyZXNvdXJjZXMvYXNzZXRzL2Nzcy9kYXNoYm9hcmQuY3NzJyxcbiAgICAgICAgJ3Jlc291cmNlcy9qcy9hcnRpY2xlcy1zaG93LmpzJyxcbiAgICAgICAgJ3Jlc291cmNlcy9hc3NldHMvanMvcGFnZXMvZGFzaGJvYXJkLmpzJyxcbiAgICAgICAgJ3Jlc291cmNlcy9hc3NldHMvY3NzL3BhZ2VzL3NjaG9vbC1jbGFzc2VzLmNzcycsXG4gICAgICAgICdyZXNvdXJjZXMvYXNzZXRzL2pzL3BhZ2VzL3BlcmZvcm1hbmNlLmpzJyxcbiAgICAgICAgJ3Jlc291cmNlcy9hc3NldHMvY3NzL3BhZ2VzL3BlcmZvcm1hbmNlLmNzcycsXG4gICAgICAgICdyZXNvdXJjZXMvYXNzZXRzL2pzL3BhZ2VzL3BlcmZvcm1hbmNlLW1ldHJpY3MuanMnLFxuICAgICAgICAncmVzb3VyY2VzL2Fzc2V0cy9qcy9wYWdlcy9uZXdzLmpzJyxcbiAgICAgICAgJ3Jlc291cmNlcy9jc3MvcGFnZXMvZGFzaGJvYXJkLmNzcycsXG4gICAgICAgICdyZXNvdXJjZXMvYXNzZXRzL2Nzcy9wYWdlcy9hYm91dC11cy5jc3MnLFxuICAgICAgICAncmVzb3VyY2VzL2Fzc2V0cy9qcy9wYWdlcy91c2VybGlzdC5qcycsXG4gICAgICAgICdyZXNvdXJjZXMvc2Nzcy9iYXNlL3BhZ2VzL25ld3Muc2NzcycsXG4gICAgICAgIC4uLnBhZ2VKc0ZpbGVzLFxuICAgICAgICAuLi52ZW5kb3JKc0ZpbGVzLFxuICAgICAgICAuLi5MaWJzSnNGaWxlcyxcbiAgICAgICAgJ3Jlc291cmNlcy9qcy9sYXJhdmVsLXVzZXItbWFuYWdlbWVudC5qcycsXG4gICAgICAgIC4uLkNvcmVTY3NzRmlsZXMsXG4gICAgICAgIC4uLlJ0bFNjc3NGaWxlcyxcbiAgICAgICAgLi4uTGlic1Njc3NGaWxlcyxcbiAgICAgICAgLi4uTGlic0Nzc0ZpbGVzLFxuICAgICAgICAuLi5Gb250c1Njc3NGaWxlcyxcblxuICAgICAgXSxcbiAgICAgIHJlZnJlc2g6IHRydWVcbiAgICB9KSxcbiAgICBsaWJzV2luZG93QXNzaWdubWVudCgpLFxuICAgIHZpdGVDb21wcmVzc2lvbigpXG4gIF0sXG4gIHJlc29sdmU6IHtcbiAgICBhbGlhczoge1xuICAgICAgJ34nOiBwYXRoLnJlc29sdmUoX19kaXJuYW1lLCAnbm9kZV9tb2R1bGVzJyksXG4gICAgICAnQCc6IHBhdGgucmVzb2x2ZShfX2Rpcm5hbWUsICdyZXNvdXJjZXMnKVxuICAgIH1cbiAgfVxufSk7XG4iXSwKICAibWFwcGluZ3MiOiAiO0FBQWtYLFNBQVMsb0JBQW9CO0FBQy9ZLE9BQU8sYUFBYTtBQUNwQixPQUFPLFVBQVU7QUFDakIsU0FBUyxZQUFZO0FBQ3JCLE9BQU8sVUFBVTtBQUNqQixPQUFPLHFCQUFxQjtBQUw1QixJQUFNLG1DQUFtQztBQVl6QyxTQUFTLGNBQWMsT0FBTztBQUM1QixTQUFPLE1BQU0sS0FBSyxJQUFJLElBQUksS0FBSyxLQUFLLEtBQUssQ0FBQyxDQUFDO0FBQzdDO0FBS0EsSUFBTSxjQUFjLGNBQWMsMEJBQTBCO0FBRzVELElBQU0sZ0JBQWdCLGNBQWMsaUNBQWlDLEVBQUUsT0FBTyxVQUFRLENBQUMsS0FBSyxTQUFTLGFBQWEsS0FBSyxDQUFDLEtBQUssU0FBUyxvQkFBb0IsQ0FBQztBQUczSixJQUFNLGNBQWMsY0FBYyxzQ0FBc0M7QUFNeEUsSUFBTSxnQkFBZ0IsY0FBYyw0Q0FBNEM7QUFDaEYsSUFBTSxlQUFlLGNBQWMsZ0RBQWdEO0FBR25GLElBQU0sZ0JBQWdCLGNBQWMsNENBQTRDO0FBQ2hGLElBQU0sZUFBZSxjQUFjLHVDQUF1QztBQUcxRSxJQUFNLGlCQUFpQixjQUFjLDZDQUE2QztBQUdsRixJQUFNLG1CQUFtQixjQUFjLGtDQUFrQztBQUd6RSxTQUFTLHVCQUF1QjtBQUM5QixTQUFPO0FBQUEsSUFDTCxNQUFNO0FBQUEsSUFFTixVQUFVLEtBQUssSUFBSTtBQUNqQixVQUFJLEdBQUcsU0FBUyxZQUFZLEdBQUc7QUFDN0IsZUFBTyxJQUFJLFFBQVEsZ0JBQWdCLGdCQUFnQjtBQUFBLE1BQ3JELFdBQVcsR0FBRyxTQUFTLFdBQVcsR0FBRztBQUNuQyxlQUFPLElBQUksV0FBVyxnQkFBZ0IsZ0JBQWdCO0FBQUEsTUFDeEQ7QUFBQSxJQUNGO0FBQUEsRUFDRjtBQUNGO0FBRUEsSUFBTyxzQkFBUSxhQUFhO0FBQUEsRUFDMUIsU0FBUztBQUFBLElBQ1AsUUFBUTtBQUFBLE1BQ04sT0FBTztBQUFBLFFBQ0w7QUFBQSxRQUNBO0FBQUEsUUFDQTtBQUFBLFFBQ0E7QUFBQSxRQUNBO0FBQUEsUUFDQTtBQUFBLFFBQ0E7QUFBQSxRQUNBO0FBQUEsUUFDQTtBQUFBLFFBQ0E7QUFBQSxRQUNBO0FBQUEsUUFDQTtBQUFBLFFBQ0E7QUFBQSxRQUNBO0FBQUEsUUFDQTtBQUFBLFFBQ0E7QUFBQSxRQUNBO0FBQUEsUUFDQTtBQUFBLFFBQ0E7QUFBQSxRQUNBO0FBQUEsUUFDQTtBQUFBLFFBQ0E7QUFBQSxRQUNBO0FBQUEsUUFDQTtBQUFBLFFBQ0E7QUFBQSxRQUNBO0FBQUEsUUFDQTtBQUFBLFFBQ0E7QUFBQSxRQUNBO0FBQUEsUUFDQTtBQUFBLFFBQ0E7QUFBQSxRQUNBO0FBQUEsUUFDQTtBQUFBLFFBQ0E7QUFBQSxRQUNBLEdBQUc7QUFBQSxRQUNILEdBQUc7QUFBQSxRQUNILEdBQUc7QUFBQSxRQUNIO0FBQUEsUUFDQSxHQUFHO0FBQUEsUUFDSCxHQUFHO0FBQUEsUUFDSCxHQUFHO0FBQUEsUUFDSCxHQUFHO0FBQUEsUUFDSCxHQUFHO0FBQUEsTUFFTDtBQUFBLE1BQ0EsU0FBUztBQUFBLElBQ1gsQ0FBQztBQUFBLElBQ0QscUJBQXFCO0FBQUEsSUFDckIsZ0JBQWdCO0FBQUEsRUFDbEI7QUFBQSxFQUNBLFNBQVM7QUFBQSxJQUNQLE9BQU87QUFBQSxNQUNMLEtBQUssS0FBSyxRQUFRLGtDQUFXLGNBQWM7QUFBQSxNQUMzQyxLQUFLLEtBQUssUUFBUSxrQ0FBVyxXQUFXO0FBQUEsSUFDMUM7QUFBQSxFQUNGO0FBQ0YsQ0FBQzsiLAogICJuYW1lcyI6IFtdCn0K
