<template>
  <div class="editor border rounded">
    <editor-menu-bar :editor="editor" v-slot="{ commands, isActive }">
      <div class="menubar border-bottom mb-0">
        <div class="btn-toolbar">
          <div class="btn-group mr-1" role="group" aria-label="Group 1">
            <button
              class="btn menubar__button"
              :class="{ 'is-active': isActive.bold() }"
              @click="commands.bold"
            >
              <i class="fas fa-bold"></i>
            </button>
            <button
              class="btn menubar__button"
              :class="{ 'is-active': isActive.italic() }"
              @click="commands.italic"
            >
              <i class="fas fa-italic"></i>
            </button>
            <button
              class="btn menubar__button"
              :class="{ 'is-active': isActive.strike() }"
              @click="commands.strike"
            >
              <i class="fas fa-strikethrough"></i>
            </button>

            <button
              class="btn menubar__button"
              :class="{ 'is-active': isActive.underline() }"
              @click="commands.underline"
            >
              <i class="fas fa-underline"></i>
            </button>
          </div>
          <div class="btn-group mr-1" role="group" aria-label="Group 2">
            <button
              class="btn menubar__button"
              :class="{ 'is-active': isActive.paragraph() }"
              @click="commands.paragraph"
            >
              <i class="fas fa-paragraph"></i>
            </button>

            <button
              class="btn menubar__button"
              :class="{ 'is-active': isActive.heading({ level: 2 }) }"
              @click="commands.heading({ level: 2 })"
            >
              H2
            </button>

            <button
              class="btn menubar__button"
              :class="{ 'is-active': isActive.heading({ level: 3 }) }"
              @click="commands.heading({ level: 3 })"
            >
              H3
            </button>

            <button
              class="btn menubar__button"
              :class="{ 'is-active': isActive.heading({ level: 4 }) }"
              @click="commands.heading({ level: 4 })"
            >
              H4
            </button>
          </div>
          <div class="btn-group mr-1" role="group" aria-label="Group 3">
            <button
              class="btn menubar__button"
              :class="{ 'is-active': isActive.bullet_list() }"
              @click="commands.bullet_list"
            >
              <i class="fas fa-list-ul"></i>
            </button>

            <button
              class="btn menubar__button"
              :class="{ 'is-active': isActive.ordered_list() }"
              @click="commands.ordered_list"
            >
              <i class="fas fa-list-ol"></i>
            </button>
            <button class="btn menubar__button" @click="commands.horizontal_rule">
              <i class="fas fa-grip-lines"></i>
            </button>
          </div>
          <div class="btn-group mr-1" role="group" aria-label="Group 4">
            <button
              class="btn menubar__button"
              :class="{ 'is-active': isActive.blockquote() }"
              @click="commands.blockquote"
            >
              <i class="fas fa-quote-right"></i>
            </button>

            <button
              class="btn menubar__button"
              :class="{ 'is-active': isActive.code() }"
              @click="commands.code"
            >
              <i class="fas fa-terminal"></i>
            </button>

            <button
              class="btn menubar__button"
              :class="{ 'is-active': isActive.code_block() }"
              @click="commands.code_block"
            >
              <i class="fas fa-code"></i>
            </button>
          </div>
          <div class="btn-group" role="group" aria-label="Group 5">
            <button class="btn menubar__button" @click="commands.undo">
              <i class="fas fa-undo"></i>
            </button>

            <button class="btn menubar__button" @click="commands.redo">
              <i class="fas fa-redo"></i>
            </button>
          </div>
        </div>
      </div>
    </editor-menu-bar>
    <editor-content
      class="editor__content bg-white p-2"
      :editor="editor"
    ></editor-content>
  </div>
</template>
<script>
import { Editor, EditorContent, EditorMenuBar } from "tiptap";
import {
  Blockquote,
  CodeBlock,
  HardBreak,
  Heading,
  HorizontalRule,
  OrderedList,
  BulletList,
  ListItem,
  TodoItem,
  TodoList,
  Bold,
  Code,
  Italic,
  Link,
  Strike,
  Underline,
  History,
} from "tiptap-extensions";
export default {
  components: {
    EditorContent,
    EditorMenuBar,
  },
  props: ["value"],
  data() {
    return {
      editor: null,
    };
  },
  mounted() {
    this.editor = new Editor({
      extensions: [
        new Blockquote(),
        new BulletList(),
        new CodeBlock(),
        new HardBreak(),
        new Heading({ levels: [1, 2, 3] }),
        new HorizontalRule(),
        new ListItem(),
        new OrderedList(),
        new TodoItem(),
        new TodoList(),
        new Link(),
        new Bold(),
        new Code(),
        new Italic(),
        new Strike(),
        new Underline(),
        new History(),
      ],
      content: this.value,
      onUpdate: ({ getHTML }) => {
        const state = getHTML();
        this.$emit("input", state);
      },
    });
  },
  beforeDestroy() {
    this.editor.destroy();
  },
};
</script>