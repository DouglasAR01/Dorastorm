<template>
  <div class="editor border rounded bg-white">
    <single-image-upload-modal ref="imgup" @confirmed="addImage" />
    <div class="menubar border-bottom mb-0" v-if="editor">
      <div class="btn-toolbar">
        <div class="btn-group mr-1" role="group" aria-label="Group 1">
          <button
            class="btn menubar__button"
            :class="{ 'is-active': editor.isActive('bold') }"
            @click="editor.chain().focus().toggleBold().run()"
          >
            <i class="fas fa-bold"></i>
          </button>
          <button
            class="btn menubar__button"
            :class="{ 'is-active': editor.isActive('italic') }"
            @click="editor.chain().focus().toggleItalic().run()"
          >
            <i class="fas fa-italic"></i>
          </button>
          <button
            class="btn menubar__button"
            :class="{ 'is-active': editor.isActive('strike') }"
            @click="editor.chain().focus().toggleStrike().run()"
          >
            <i class="fas fa-strikethrough"></i>
          </button>

          <button
            class="btn menubar__button"
            :class="{ 'is-active': editor.isActive('underline') }"
            @click="editor.chain().focus().toggleUnderline().run()"
          >
            <i class="fas fa-underline"></i>
          </button>
        </div>
        <div class="btn-group mr-1" role="group" aria-label="Group 2">
          <button
            class="btn menubar__button"
            :class="{ 'is-active': editor.isActive('paragraph') }"
            @click="editor.chain().focus().setParagraph().run()"
          >
            <i class="fas fa-paragraph"></i>
          </button>

          <button
            class="btn menubar__button"
            :class="{ 'is-active': editor.isActive('heading', { level: 2 }) }"
            @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
          >
            H2
          </button>

          <button
            class="btn menubar__button"
            :class="{ 'is-active': editor.isActive('heading', { level: 3 }) }"
            @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
          >
            H3
          </button>

          <button
            class="btn menubar__button"
            :class="{ 'is-active': editor.isActive('heading', { level: 4 }) }"
            @click="editor.chain().focus().toggleHeading({ level: 4 }).run()"
          >
            H4
          </button>
        </div>
        <div class="btn-group mr-1" role="group" aria-label="Group 3">
          <button
            class="btn menubar__button"
            :class="{ 'is-active': editor.isActive('bulletList') }"
            @click="editor.chain().focus().toggleBulletList().run()"
          >
            <i class="fas fa-list-ul"></i>
          </button>

          <button
            class="btn menubar__button"
            :class="{ 'is-active': editor.isActive('orderedList') }"
            @click="editor.chain().focus().toggleOrderedList().run()"
          >
            <i class="fas fa-list-ol"></i>
          </button>
          <button
            class="btn menubar__button"
            @click="editor.chain().focus().setHorizontalRule().run()"
          >
            <i class="fas fa-grip-lines"></i>
          </button>
        </div>
        <div class="btn-group mr-1">
          <button
            class="btn menubar__button"
            @click="tryImageUpload()"
          >
            <i class="far fa-image"></i>
          </button>
        </div>
        <div class="btn-group mr-1" role="group" aria-label="Group 4">
          <button
            class="btn menubar__button"
            :class="{ 'is-active': editor.isActive('blockquote') }"
            @click="editor.chain().focus().toggleBlockquote().run()"
          >
            <i class="fas fa-quote-right"></i>
          </button>

          <button
            class="btn menubar__button"
            :class="{ 'is-active': editor.isActive('code') }"
            @click="editor.chain().focus().toggleCode().run()"
          >
            <i class="fas fa-terminal"></i>
          </button>

          <button
            class="btn menubar__button"
            :class="{ 'is-active': editor.isActive('codeBlock') }"
            @click="editor.chain().focus().toggleCodeBlock().run()"
          >
            <i class="fas fa-code"></i>
          </button>
        </div>
        <div class="btn-group" role="group" aria-label="Group 5">
          <button
            class="btn menubar__button"
            @click="editor.chain().focus().undo().run()"
          >
            <i class="fas fa-undo"></i>
          </button>

          <button
            class="btn menubar__button"
            @click="editor.chain().focus().redo().run()"
          >
            <i class="fas fa-redo"></i>
          </button>
        </div>
      </div>
    </div>
    <editor-content
      class="editor__content bg-white p-2"
      :editor="editor"
    ></editor-content>
  </div>
</template>
<script>
import StarterKit from "@tiptap/starter-kit";
import Underline from "@tiptap/extension-underline";
import Image from "@tiptap/extension-image";
import { Editor, EditorContent } from "@tiptap/vue-2";
import SingleImageUploadModal from "./SingleImageUploadModal.vue";
export default {
  components: {
    EditorContent,
    SingleImageUploadModal,
  },
  props: {
    value: {
      type: String,
    },
  },
  data() {
    return {
      editor: null,
    };
  },
  mounted() {
    this.editor = new Editor({
      extensions: [
        StarterKit.configure({
          heading: {
            levels: [2, 3, 4],
          },
        }),
        Underline,
        Image,
      ],
      content: this.value,
      onUpdate: ({ editor }) => {
        const state = editor.getHTML();
        this.$emit("input", state);
      },
    });
  },
  beforeUnmount() {
    this.editor.destroy();
  },
  methods: {
    tryImageUpload() {
      this.$refs.imgup.show();
    },
    addImage(payload) {
      this.editor.chain().focus().setImage(payload).run();
    },
  },
};
</script>