<template>
  <component
    v-if="globalComponentList[defaultComponent]"
    v-bind:is="globalComponentList[defaultComponent]"
  ></component>
  <component v-else v-bind:is="defaultComponent"></component>
</template>

<script>
import AuthForgotPassword from "@badaso/pages/auth/forgot-password";
import AuthLogin from "@badaso/auth/login";
import AuthRegister from "@badaso/auth/register";
import AuthResetPassword from "@badaso/auth/reset-password";
import AuthVerify from "@badaso/auth/verify";

import Home from "@badaso/home";
import UserProfile from "@badaso/user/profile";

import ActivityLogBrowse from "@badaso/activity-log/browse";
import ActivityLogRead from "@badaso/activity-log/read";
import CrudGeneratedBrowse from "@badaso/crud-generated/browse";
import CrudGeneratedBrowseBin from "@badaso/crud-generated/browse-bin";
import CrudGeneratedRead from "@badaso/crud-generated/read";
import CrudGeneratedEdit from "@badaso/crud-generated/edit";
import CrudGeneratedAdd from "@badaso/crud-generated/add";
import CrudGeneratedSort from "@badaso/crud-generated/sort";
import CrudManagementBrowse from "@badaso/crud-management/browse";
import CrudManagementRead from "@badaso/crud-management/read";
import CrudManagementEdit from "@badaso/crud-management/edit";
import CrudManagementAdd from "@badaso/crud-management/add";
import DatabaseManagementBrowse from "@badaso/database-management/browse";
import DatabaseManagementAdd from "@badaso/database-management/add";
import DatabaseManagementAlter from "@badaso/database-management/edit";
import MenuManagementBrowse from "@badaso/menu-management/browse";
import MenuManagementEdit from "@badaso/menu-management/edit";
import MenuManagementAdd from "@badaso/menu-management/add";
import MenuManagementBuilder from "@badaso/menu-management/builder";
import MenuManagementPermissions from "@badaso/menu-management/permissions";
import PermissionManagementBrowse from "@badaso/permission-management/browse";
import PermissionManagementRead from "@badaso/permission-management/read";
import PermissionManagementEdit from "@badaso/permission-management/edit";
import PermissionManagementAdd from "@badaso/permission-management/add";
import RoleManagementBrowse from "@badaso/role-management/browse";
import RoleManagementRead from "@badaso/role-management/read";
import RoleManagementEdit from "@badaso/role-management/edit";
import RoleManagementAdd from "@badaso/role-management/add";
import RoleManagementPermissions from "@badaso/role-management/permissions";
import ConfigurationBrowse from "@badaso/configuration/browse";
import ConfigurationAdd from "@badaso/configuration/add";
import UserManagementBrowse from "@badaso/user-management/browse";
import UserManagementRead from "@badaso/user-management/read";
import UserManagementEdit from "@badaso/user-management/edit";
import UserManagementAdd from "@badaso/user-management/add";
import UserManagementRoles from "@badaso/user-management/roles";
import FileManagerBrowse from "@badaso/file-manager/browse";
import ImageManagerBrowse from "@badaso/image-manager/browse";
import LogViewerBrowse from "@badaso/log-viewer/browse";
import NotificationBrowse from "@badaso/notification/browse";
import DataPendingAddBrowse from "@badaso/data-pending-add/browse";
import DataPendingEditRead from "@badaso/data-pending-edit/read";
import ApiDocsBrowse from "@badaso/api-documentation/browse";

export default {
  components: {
    AuthForgotPassword,
    AuthLogin,
    AuthRegister,
    AuthResetPassword,
    AuthVerify,
    ActivityLogBrowse,
    ActivityLogRead,
    Home,
    UserProfile,
    CrudGeneratedBrowse,
    CrudGeneratedBrowseBin,
    CrudGeneratedRead,
    CrudGeneratedEdit,
    CrudGeneratedAdd,
    CrudGeneratedSort,
    CrudManagementBrowse,
    CrudManagementRead,
    CrudManagementEdit,
    CrudManagementAdd,
    DatabaseManagementBrowse,
    DatabaseManagementAdd,
    DatabaseManagementAlter,
    MenuManagementBrowse,
    MenuManagementEdit,
    MenuManagementAdd,
    MenuManagementBuilder,
    MenuManagementPermissions,
    PermissionManagementBrowse,
    PermissionManagementRead,
    PermissionManagementEdit,
    PermissionManagementAdd,
    RoleManagementBrowse,
    RoleManagementRead,
    RoleManagementEdit,
    RoleManagementAdd,
    RoleManagementPermissions,
    ConfigurationBrowse,
    ConfigurationAdd,
    UserManagementBrowse,
    UserManagementRead,
    UserManagementEdit,
    UserManagementAdd,
    UserManagementRoles,
    FileManagerBrowse,
    ImageManagerBrowse,
    LogViewerBrowse,
    NotificationBrowse,
    DataPendingAddBrowse,
    DataPendingEditRead,
    ApiDocsBrowse,
  },
  name: "ActivityLogIndex",
  data: () => ({
    globalComponentList: {},
    defaultComponent: null,
  }),
  mounted() {
    const routeName = this.$route.name;
    const componentName = this.$caseConvert.kebab(routeName);
    const slug = this.$route.params ? this.$route.params.slug : "";
    this.globalComponentList = this.constructor.superOptions.components;

    if (routeName === "CrudGeneratedBrowse") {
      const generatedComponentName = slug + "-browse";
      if (this.globalComponentList[generatedComponentName]) {
        this.defaultComponent = generatedComponentName;
      } else {
        this.defaultComponent = componentName;
      }
    } else if (routeName === "CrudGeneratedRead") {
      const generatedComponentName = slug + "-read";
      if (this.globalComponentList[generatedComponentName]) {
        this.defaultComponent = generatedComponentName;
      } else {
        this.defaultComponent = componentName;
      }
    } else if (routeName === "CrudGeneratedEdit") {
      const generatedComponentName = slug + "-edit";
      if (this.globalComponentList[generatedComponentName]) {
        this.defaultComponent = generatedComponentName;
      } else {
        this.defaultComponent = componentName;
      }
    } else if (routeName === "CrudGeneratedAdd") {
      const generatedComponentName = slug + "-add";
      if (this.globalComponentList[generatedComponentName]) {
        this.defaultComponent = generatedComponentName;
      } else {
        this.defaultComponent = componentName;
      }
    } else if (routeName === "CrudGeneratedSort") {
      const generatedComponentName = slug + "-sort";
      if (this.globalComponentList[generatedComponentName]) {
        this.defaultComponent = generatedComponentName;
      } else {
        this.defaultComponent = componentName;
      }
    } else {
      this.defaultComponent = componentName;
    }
  },
  methods: {},
};
</script>
